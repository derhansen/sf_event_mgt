<?php
namespace SKYFILLERS\SfEventMgt\Controller;


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

use SKYFILLERS\SfEventMgt\Domain\Model\Event;
use SKYFILLERS\SfEventMgt\Domain\Model\Registration;
use SKYFILLERS\SfEventMgt\Util\RegistrationResult;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * EventController
 */
class EventController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * eventRepository
	 *
	 * @var \SKYFILLERS\SfEventMgt\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;

	/**
	 * Registration repository
	 *
	 * @var \SKYFILLERS\SfEventMgt\Domain\Repository\RegistrationRepository
	 * @inject
	 */
	protected $registrationRepository = NULL;

	/**
	 * Notification Service
	 *
	 * @var \SKYFILLERS\SfEventMgt\Service\NotificationService
	 * @inject
	 */
	protected $notificationService = NULL;

	/**
	 * Create a demand object with the given settings
	 *
	 * @param array $settings
	 * @return \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand
	 */
	public function createDemandObjectFromSettings($settings) {
		$demand = $this->objectManager->get('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand');
		$demand->setDisplayMode($settings['displayMode']);
		$demand->setStoragePage($settings['storagePage']);
		$demand->setCategory($settings['category']);
		return $demand;
	}

	/**
	 * List view
	 *
	 * @return void
	 */
	public function listAction() {
		$demand = $this->createDemandObjectFromSettings($this->settings);

		$events = $this->eventRepository->findDemanded($demand);
		$this->view->assign('events', $events);
	}

	/**
	 * Detail view for an event
	 *
	 * @param $event \SKYFILLERS\SfEventMgt\Domain\Model\Event
	 * @return void
	 */
	public function detailAction(Event $event) {
		$this->view->assign('event', $event);
	}

	/**
	 * Registration view for an event
	 *
	 * @param $event \SKYFILLERS\SfEventMgt\Domain\Model\Event
	 * @return void
	 */
	public function registrationAction(Event $event) {
		$this->view->assign('event', $event);
	}

	/**
	 * Saves the registration
	 *
	 * @param $registration \SKYFILLERS\SfEventMgt\Domain\Model\Registration
	 * @param $event \SKYFILLERS\SfEventMgt\Domain\Model\Event
	 * @return void
	 */
	public function saveRegistrationAction(Registration $registration, Event $event) {
		$success = TRUE;
		$result = RegistrationResult::REGISTRATION_SUCCESSFULL;
		if ($event->getStartdate() < new \DateTime()) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED;
		} elseif ($event->getRegistration()->count() >= $event->getMaxParticipants()
			&& $event->getMaxParticipants() > 0) {
			$success = FALSE;
			$result = RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS;
		}

		// Save registration if no errors
		if ($success) {
			$registration->setEvent($event);
			$registration->setPid($event->getPid());
			$this->registrationRepository->add($registration);

			// Send notifications to user and admin
			$this->notificationService->sendUserConfirmationMessage($event, $registration, $this->settings);
			$this->notificationService->sendAdminNewRegistrationMessage($event, $registration, $this->settings);
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
			case RegistrationResult::REGISTRATION_SUCCESSFULL:
				$message = LocalizationUtility::translate('event.message.registrationsuccessfull', 'SfEventMgt');
				break;
			case RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED:
				$message = LocalizationUtility::translate('event.message.registrationfailedeventexpired',
					'SfEventMgt');
				break;
			case RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS:
				$message = LocalizationUtility::translate('event.message.registrationfailedmaxparticipants',
					'SfEventMgt');
				break;
			default:
				$message = '';
		}

		$this->view->assign('message', $message);
	}
}
