<?php
namespace SKYFILLERS\SfEventMgt\Service;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
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
	 * Email Service
	 *
	 * @var \SKYFILLERS\SfEventMgt\Service\EmailService
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
	 * Sends a confirmation message for new registrations
	 *
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Event $event
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Registration $registration
	 * @param array $settings
	 *
	 * @return bool TRUE if successfull, else FALSE
	 */
	public function sendUserConfirmationMessage($event, $registration, $settings) {
		$template = 'Notification/User/RegistrationNew.html';

		if (is_null($event) || is_null($registration || !is_array($settings))) {
			return FALSE;
		}

		if (GeneralUtility::validEmail($registration->getEmail()) &&
			GeneralUtility::validEmail($settings['notification']['senderEmail'])) {
			$body = $this->getNotificationBody($event, $registration, $template, $settings);
			return $this->emailService->sendEmailMessage(
				$settings['notification']['senderEmail'],
				$registration->getEmail(),
				$settings['notification']['registrationNew']['userSubject'],
				$body,
				$settings['notification']['senderName']
			);
		} else {
			return FALSE;
		}
	}

	/**
	 * Sends a message to the admin if a new registration has been created
	 *
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Event $event
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Registration $registration
	 * @param array $settings
	 *
	 * @return bool TRUE if successfull, else FALSE
	 */
	public function sendAdminNewRegistrationMessage($event, $registration, $settings) {
		$template = 'Notification/Admin/RegistrationNew.html';

		if (is_null($event) || is_null($registration || !is_array($settings))) {
			return FALSE;
		}

		if (GeneralUtility::validEmail($settings['notification']['senderEmail']) &&
			GeneralUtility::validEmail($settings['notification']['adminEmail'])) {
			$body = $this->getNotificationBody($event, $registration, $template, $settings);
			return $this->emailService->sendEmailMessage(
				$settings['notification']['senderEmail'],
				$settings['notification']['adminEmail'],
				$settings['notification']['registrationNew']['adminSubject'],
				$body,
				$settings['notification']['senderName']
			);
		} else {
			return FALSE;
		}
	}

	/**
	 * Returns the rendered HTML for the given template
	 *
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Event $event
	 * @param \SKYFILLERS\SfEventMgt\Domain\Model\Registration $registration
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
			'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid())
		));
		$emailBody = $emailView->render();
		return $emailBody;
	}

}