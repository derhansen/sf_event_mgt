<?php
namespace DERHANSEN\SfEventMgt\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DERHANSEN\SfEventMgt\Utility\MessageType;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * NotificationService
 */
class NotificationService {

	/**
	 * The object manager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * The configuration manager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Registration repository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
	 * @inject
	 */
	protected $registrationRepository = NULL;

	/**
	 * Email Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\EmailService
	 * @inject
	 */
	protected $emailService;

	/**
	 * Hash Service
	 *
	 * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
	 * @inject
	 */
	protected $hashService;

	/**
	 * customNotificationLogRepository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository
	 * @inject
	 */
	protected $customNotificationLogRepository = NULL;

	/**
	 * Sends a custom notification defined by the given customNotification key
	 * to all confirmed users of the event
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param string $customNotification
	 * @param array $settings
	 * @return int Number of notifications sent
	 */
	public function sendCustomNotification($event, $customNotification, $settings) {
		if (is_null($event) || $customNotification == '' || $settings == '' || !is_array($settings)) {
			return 0;
		}
		$count = 0;

		$constraints = $settings['notification']['customNotifications'][$customNotification]['constraints'];
		$registrations = $this->registrationRepository->findNotificationRegistrations($event, $constraints);
		foreach ($registrations as $registration) {
			/** @var \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration */
			if ($registration->isConfirmed() && !$registration->isIgnoreNotifications()) {
				$result = $this->sendUserMessage(
					$event,
					$registration,
					$settings,
					MessageType::CUSTOM_NOTIFICATION,
					$customNotification
				);
				if ($result) {
					$count += 1;
				}
			}
		}
		return $count;
	}

	/**
	 * Adds a logentry to the custom notification log
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param string $details
	 * @param int $emailsSent
	 * @return void
	 */
	public function createCustomNotificationLogentry($event, $details, $emailsSent) {
		$notificationlogEntry = new \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog();
		$notificationlogEntry->setEvent($event);
		$notificationlogEntry->setDetails($details);
		$notificationlogEntry->setEmailsSent($emailsSent);
		$notificationlogEntry->setCruserId($GLOBALS['BE_USER']->user['uid']);
		$this->customNotificationLogRepository->add($notificationlogEntry);
	}

	/**
	 * Sends a message to the user based on the given type
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @param array $settings
	 * @param int $type
	 * @param string $customNotification
	 *
	 * @return bool TRUE if successful, else FALSE
	 */
	public function sendUserMessage($event, $registration, $settings, $type, $customNotification = '') {
		$template = 'Notification/User/RegistrationNew.html';
		$subject = $settings['notification']['registrationNew']['userSubject'];
		switch ($type) {
			case MessageType::REGISTRATION_CONFIRMED:
				$template = 'Notification/User/RegistrationConfirmed.html';
				$subject = $settings['notification']['registrationConfirmed']['userSubject'];
				break;
			case MessageType::CUSTOM_NOTIFICATION:
				$template = 'Notification/User/Custom/' . $settings['notification']['customNotifications'][$customNotification]['template'];
				$subject = $settings['notification']['customNotifications'][$customNotification]['subject'];
				break;
			case MessageType::REGISTRATION_NEW:
			default:
		}

		if (is_null($event) || is_null($registration) || !is_array($settings) || (substr($template, -5) != '.html')) {
			return FALSE;
		}

		if (GeneralUtility::validEmail($registration->getEmail()) &&
			GeneralUtility::validEmail($settings['notification']['senderEmail']) &&
			!$registration->isIgnoreNotifications()) {
			$body = $this->getNotificationBody($event, $registration, $template, $settings);
			return $this->emailService->sendEmailMessage(
				$settings['notification']['senderEmail'],
				$registration->getEmail(),
				$subject,
				$body,
				$settings['notification']['senderName']
			);
		}
		return FALSE;
	}

	/**
	 * Sends a message to the admin based on the given type
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @param array $settings
	 * @param int $type
	 *
	 * @return bool TRUE if successful, else FALSE
	 */
	public function sendAdminMessage($event, $registration, $settings, $type) {
		$template = 'Notification/Admin/RegistrationNew.html';
		$subject = $settings['notification']['registrationNew']['adminSubject'];
		switch ($type) {
			case MessageType::REGISTRATION_CONFIRMED:
				$template = 'Notification/Admin/RegistrationConfirmed.html';
				$subject = $settings['notification']['registrationConfirmed']['adminSubject'];
				break;
			case MessageType::REGISTRATION_NEW:
			default:
		}

		if (is_null($event) || is_null($registration || !is_array($settings))) {
			return FALSE;
		}

		if (GeneralUtility::validEmail($settings['notification']['senderEmail']) &&
			GeneralUtility::validEmail($settings['notification']['adminEmail'])) {
			$body = $this->getNotificationBody($event, $registration, $template, $settings);
			return $this->emailService->sendEmailMessage(
				$settings['notification']['senderEmail'],
				$settings['notification']['adminEmail'],
				$subject,
				$body,
				$settings['notification']['senderName']
			);
		}
		return FALSE;
	}

	/**
	 * Returns the rendered HTML for the given template
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @param string $template
	 * @param array $settings
	 *
	 * @return string
	 */
	protected function getNotificationBody($event, $registration, $template, $settings) {
		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $emailView */
		$emailView = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$emailView->setFormat('html');
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$templateRootPath = GeneralUtility::getFileAbsFileName(
			$extbaseFrameworkConfiguration['plugin.']['tx_sfeventmgt.']['view.']['templateRootPath']);
		$layoutRootPath = GeneralUtility::getFileAbsFileName(
			$extbaseFrameworkConfiguration['plugin.']['tx_sfeventmgt.']['view.']['layoutRootPath']);

		$emailView->setLayoutRootPath($layoutRootPath);
		$emailView->setTemplatePathAndFilename($templateRootPath . $template);
		$emailView->assignMultiple(array(
			'event' => $event,
			'registration' => $registration,
			'settings' => $settings,
			'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid()),
			'reghmac' => $this->hashService->appendHmac((string)$registration->getUid())
		));
		$emailBody = $emailView->render();
		return $emailBody;
	}

}