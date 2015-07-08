<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

/**
 * EventController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Configuration Manager
	 *
	 * @var ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * CacheService
	 *
	 * @var \TYPO3\CMS\Extbase\Service\CacheService
	 * @inject
	 */
	protected $cacheService;

	/**
	 * EventRepository
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
	 * Location repository
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository
	 * @inject
	 */
	protected $locationRepository = NULL;

	/**
	 * Notification Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\NotificationService
	 * @inject
	 */
	protected $notificationService = NULL;

	/**
	 * ICalendar Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\ICalendarService
	 * @inject
	 */
	protected $icalendarService = NULL;

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
	 * RegistrationService
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
	 * @inject
	 */
	protected $registrationService = NULL;

	/**
	 * Properties in this array will be ignored by overwriteDemandObject()
	 *
	 * @var array
	 */
	protected $ignoredSettingsForOverwriteDemand = array('storagePage');

	/**
	 * Create a demand object with the given settings
	 *
	 * @param array $settings The settings
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
	 */
	public function createDemandObjectFromSettings(array $settings) {
		/** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand */
		$demand = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setDisplayMode($settings['displayMode']);
		$demand->setStoragePage($settings['storagePage']);
		$demand->setCategory($settings['category']);
		$demand->setTopEventRestriction((int)$settings['topEventRestriction']);
		$demand->setOrderField($settings['orderField']);
		$demand->setOrderDirection($settings['orderDirection']);
		$demand->setQueryLimit($settings['queryLimit']);
		$demand->setLocation($settings['location']);
		return $demand;
	}

	/**
	 * Overwrites a given demand object by an propertyName =>  $propertyValue array
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand Demand
	 * @param array $overwriteDemand OwerwriteDemand
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
	 */
	protected function overwriteDemandObject(EventDemand $demand, array $overwriteDemand) {
		foreach ($this->ignoredSettingsForOverwriteDemand as $property) {
			unset($overwriteDemand[$property]);
		}

		foreach ($overwriteDemand as $propertyName => $propertyValue) {
			\TYPO3\CMS\Extbase\Reflection\ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
		}
		return $demand;
	}

	/**
	 * List view
	 *
	 * @param array $overwriteDemand OverwriteDemand
	 *
	 * @return void
	 */
	public function listAction(array $overwriteDemand = NULL) {
		$demand = $this->createDemandObjectFromSettings($this->settings);
		if ($this->settings['disableOverrideDemand'] != 1 && $overwriteDemand !== NULL) {
			$demand = $this->overwriteDemandObject($demand, $overwriteDemand);
		}
		$events = $this->eventRepository->findDemanded($demand);
		$categories = $this->categoryRepository->findAll();
		$locations = $this->locationRepository->findAll();
		$this->view->assign('events', $events);
		$this->view->assign('categories', $categories);
		$this->view->assign('locations', $locations);
		$this->view->assign('overwriteDemand', $overwriteDemand);
	}

	/**
	 * Detail view for an event
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
	 *
	 * @return void
	 */
	public function detailAction(Event $event = NULL) {
		$this->view->assign('event', $event);
	}

	/**
	 * Initiates the iCalendar download for the given event
	 *
	 * @param Event $event The event
	 *
	 * @return bool
	 */
	public function icalDownloadAction(Event $event) {
		$this->icalendarService->downloadiCalendarFile($event);
		return FALSE;
	}

	/**
	 * Registration view for an event
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
	 *
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
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
	 * @validate $registration \DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator
	 *
	 * @return void
	 */
	public function saveRegistrationAction(Registration $registration, Event $event) {
		$autoConfirmation = (bool)$this->settings['registration']['autoConfirmation'];
		$result = RegistrationResult::REGISTRATION_SUCCESSFUL;
		$success = $this->checkRegistrationSuccess($event, $registration, $result);

		// Save registration if no errors
		if ($success) {
			$linkValidity = (int)$this->settings['confirmation']['linkValidity'];
			if ($linkValidity === 0) {
				// Use 3600 seconds as default value if not set
				$linkValidity = 3600;
			}
			$confirmationUntil = new \DateTime();
			$confirmationUntil->add(new \DateInterval('PT' . $linkValidity . 'S'));

			$registration->setEvent($event);
			$registration->setPid($event->getPid());
			$registration->setConfirmationUntil($confirmationUntil);
			$registration->_setProperty('_languageUid', $GLOBALS['TSFE']->sys_language_uid);
			$this->registrationRepository->add($registration);

			// Persist registration, so we have an UID
			$this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager')->persistAll();

			// Send notifications to user and admin if confirmation link should be sent
			if (!$autoConfirmation) {
				$this->notificationService->sendUserMessage($event, $registration, $this->settings,
					MessageType::REGISTRATION_NEW);
				$this->notificationService->sendAdminMessage($event, $registration, $this->settings,
					MessageType::REGISTRATION_NEW);
			}

			// Create given amount of registrations if necessary
			if ($registration->getAmountOfRegistrations() > 1) {
				$this->registrationService->createDependingRegistrations($registration);
			}

			// Clear cache for configured pages
			$pidList = $this->settingsService->getClearCacheUids($this->settings);
			if (count($pidList) > 0) {
				$this->cacheService->clearPageCache($pidList);
			}
		}

		if ($autoConfirmation && $success) {
			$this->redirect('confirmRegistration', NULL, NULL,
				array('reguid' => $registration->getUid(),
					'hmac' => $this->hashService->generateHmac('reg-' . $registration->getUid())));
		} else {
			$this->redirect('saveRegistrationResult', NULL, NULL,
				array('result' => $result));
		}
	}

	/**
	 * Checks, if the registration can successfully be created. Note, that
	 * $result is passed by reference!
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration Registration
	 * @param RegistrationResult $result Result
	 *
	 * @return bool
	 */
	protected function checkRegistrationSuccess(Event $event, Registration $registration, &$result) {
		$success = TRUE;
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
		} elseif ($event->getFreePlaces() < $registration->getAmountOfRegistrations()) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES;
		} elseif ($event->getMaxRegistrationsPerUser() < $registration->getAmountOfRegistrations()) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED;
		}
		return $success;
	}

	/**
	 * Shows the result of the saveRegistrationAction
	 *
	 * @param int $result Result
	 *
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
			case RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES:
				$messageKey = 'event.message.registrationfailednotenoughfreeplaces';
				$titleKey = 'registrationResult.title.failed';
				break;
			case RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED:
				$messageKey = 'event.message.registrationfailedmaxamountregistrationsexceeded';
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
		/* @var $registration Registration */
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

			// Confirm registrations depending on main registration if necessary
			if ($registration->getAmountOfRegistrations() > 1) {
				$this->registrationService->confirmDependingRegistrations($registration);
			}
		}
		$this->view->assign('messageKey', $messageKey);
		$this->view->assign('titleKey', $titleKey);
	}
}
