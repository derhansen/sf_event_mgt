<?php
namespace DERHANSEN\SfEventMgt\Controller;

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

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Configuration Manager
	 *
	 * @var ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\CacheService
	 * @inject
	 */
	protected $cacheService;

	/**
	 * eventRepository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;

	/**
	 * Registration repository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
	 * @inject
	 */
	protected $registrationRepository = NULL;

	/**
	 * Category repository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository = NULL;

	/**
	 * Notification Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\NotificationService
	 * @inject
	 */
	protected $notificationService = NULL;

	/**
	 * Settings Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\SettingsService
	 * @inject
	 */
	protected $settingsService = NULL;

	/**
	 * Hash Service
	 *
	 * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
	 * @inject
	 */
	protected $hashService;

	/**
	 * Create a demand object with the given settings
	 *
	 * @param array $settings
	 * @param int $category
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
	 */
	public function createDemandObjectFromSettings($settings, $category = 0) {
		if ($category === 0) {
			$category = $settings['category'];
		}
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setDisplayMode($settings['displayMode']);
		$demand->setStoragePage($settings['storagePage']);
		$demand->setCategory($category);
		$demand->setTopEventRestriction((int)$settings['topEventRestriction']);
		$demand->setOrderField($settings['orderField']);
		$demand->setOrderDirection($settings['orderDirection']);
		$demand->setQueryLimit($settings['queryLimit']);
		return $demand;
	}

	/**
	 * List view
	 *
	 * @param int $category
	 * @return void
	 */
	public function listAction($category = 0) {
		$demand = $this->createDemandObjectFromSettings($this->settings, $category);
		$events = $this->eventRepository->findDemanded($demand);
		$categories = $this->categoryRepository->findAll();
		$this->view->assign('events', $events);
		$this->view->assign('categories', $categories);
		$this->view->assign('selectedCategoryUid', $category);
	}

	/**
	 * Detail view for an event
	 *
	 * @param $event \DERHANSEN\SfEventMgt\Domain\Model\Event
	 * @return void
	 */
	public function detailAction(Event $event) {
		$this->view->assign('event', $event);
	}

	/**
	 * Registration view for an event
	 *
	 * @param $event \DERHANSEN\SfEventMgt\Domain\Model\Event
	 * @return void
	 */
	public function registrationAction(Event $event) {
		$this->view->assign('event', $event);
	}

	/**
	 * Set date format for field dateOfBirth
	 *
	 * @return void
	 */
	public function initializeSaveRegistrationAction() {
		$this->arguments->getArgument('registration')
			->getPropertyMappingConfiguration()->forProperty('dateOfBirth')
			->setTypeConverterOption(
				'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
				DateTimeConverter::CONFIGURATION_DATE_FORMAT,
				$this->settings['registration']['formatDateOfBirth']
			);
	}

	/**
	 * Saves the registration
	 *
	 * @param $registration \DERHANSEN\SfEventMgt\Domain\Model\Registration
	 * @param $event \DERHANSEN\SfEventMgt\Domain\Model\Event
	 * @validate $registration \DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator
	 * @return void
	 */
	public function saveRegistrationAction(Registration $registration, Event $event) {
		$success = TRUE;
		$result = RegistrationResult::REGISTRATION_SUCCESSFUL;
		if ($event->getEnableRegistration() === FALSE) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_NOT_ENABLED;
		} elseif ($event->getRegistrationDeadline() != NULL && $event->getRegistrationDeadline() < new \DateTime()) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED;
		} elseif ($event->getStartdate() < new \DateTime()) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED;
		} elseif ($event->getRegistration()->count() >= $event->getMaxParticipants()
			&& $event->getMaxParticipants() > 0) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS;
		}

		// Save registration if no errors
		if ($success) {
			$linkValidity = $this->settings['confirmation']['linkValidity'];
			if ($linkValidity === '' || !is_int($linkValidity)) {
				// Use 3600 seconds as default value if not set
				$linkValidity = 3600;
			}
			$confirmationUntil = new \DateTime();
			$confirmationUntil->add(new \DateInterval('PT' . $linkValidity . 'S'));

			$registration->setEvent($event);
			$registration->setPid($event->getPid());
			$registration->setConfirmationUntil($confirmationUntil);
			$this->registrationRepository->add($registration);

			// Persist registration, so we have an UID
			$this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

			// Send notifications to user and admin
			$this->notificationService->sendUserMessage($event, $registration, $this->settings,
				MessageType::REGISTRATION_NEW);
			$this->notificationService->sendAdminMessage($event, $registration, $this->settings,
				MessageType::REGISTRATION_NEW);

			// Clear cache for configured pages
			$pidList = $this->settingsService->getClearCacheUids($this->settings);
			if (count($pidList) > 0) {
				$this->cacheService->clearPageCache($pidList);
			}
		}

		$this->redirect('saveRegistrationResult', NULL, NULL,
			array('result' => $result));
	}

	/**
	 * Shows the result of the saveRegistrationAction
	 *
	 * @param int $result
	 * @return void
	 */
	public function saveRegistrationResultAction($result) {
		switch ($result) {
			case RegistrationResult::REGISTRATION_SUCCESSFUL:
				$messageKey = 'event.message.registrationsuccessful';
				$titleKey = 'registrationResult.title.successful';
				break;
			case RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED:
				$messageKey = 'event.message.registrationfailedeventexpired';
				$titleKey = 'registrationResult.title.failed';
				break;
			case RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS:
				$messageKey = 'event.message.registrationfailedmaxparticipants';
				$titleKey = 'registrationResult.title.failed';
				break;
			case RegistrationResult::REGISTRATION_NOT_ENABLED:
				$messageKey = 'event.message.registrationfailednotenabled';
				$titleKey = 'registrationResult.title.failed';
				break;
			case RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED:
				$messageKey = 'event.message.registrationfaileddeadlineexpired';
				$titleKey = 'registrationResult.title.failed';
				break;
			default:
				$messageKey = '';
				$titleKey = '';
		}

		$this->view->assign('messageKey', $messageKey);
		$this->view->assign('titleKey', $titleKey);
	}

	/**
	 * Confirms the registration if possible and sends e-mails to admin and user
	 *
	 * @param int $reguid UID of registration
	 * @param string $hmac HMAC for parameters
	 *
	 * @return void
	 */
	public function confirmRegistrationAction($reguid, $hmac) {
		/** @var Registration $registration */
		$registration = NULL;
		$failed = FALSE;
		$messageKey = 'event.message.confirmation_successful';
		$titleKey = 'confirmRegistration.title.successful';

		if (!$this->hashService->validateHmac('reg-' . $reguid, $hmac)) {
			$failed = TRUE;
			$messageKey = 'event.message.confirmation_failed_wrong_hmac';
			$titleKey = 'confirmRegistration.title.failed';
		} else {
			$registration = $this->registrationRepository->findByUid($reguid);
		}

		if (!$failed && is_null($registration)) {
			$failed = TRUE;
			$messageKey = 'event.message.confirmation_failed_registration_not_found';
			$titleKey = 'confirmRegistration.title.failed';
		}

		if (!$failed && $registration->getConfirmationUntil() < new \DateTime()) {
			$failed = TRUE;
			$messageKey = 'event.message.confirmation_failed_confirmation_until_expired';
			$titleKey = 'confirmRegistration.title.failed';
		}

		if (!$failed && $registration->getConfirmed() === TRUE) {
			$failed = TRUE;
			$messageKey = 'event.message.confirmation_failed_already_confirmed';
			$titleKey = 'confirmRegistration.title.failed';
		}

		if ($failed === FALSE) {
			$registration->setConfirmed(TRUE);
			$this->registrationRepository->update($registration);

			// Send notifications to user and admin
			$this->notificationService->sendUserMessage($registration->getEvent(), $registration, $this->settings,
				MessageType::REGISTRATION_CONFIRMED);
			$this->notificationService->sendAdminMessage($registration->getEvent(), $registration, $this->settings,
				MessageType::REGISTRATION_CONFIRMED);
		}
		$this->view->assign('messageKey', $messageKey);
		$this->view->assign('titleKey', $titleKey);
	}
}
