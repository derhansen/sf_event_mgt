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
	 * List view
	 *
	 * @return void
	 */
	public function listAction() {
		$events = $this->eventRepository->findAll();
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
		$message = '';
		$success = TRUE;
		if ($event->getStartdate() < new \DateTime()) {
			$message = LocalizationUtility::translate('event.message.registrationfailedeventexpired', 'SfEventMgt');
			$success = FALSE;
		} elseif ($event->getRegistration()->count() >= $event->getMaxParticipants()) {
			$message = LocalizationUtility::translate('event.message.registrationfailedmaxparticipants', 'SfEventMgt');
			$success = FALSE;
		}

		// Only save new registration, if no logical or validation errors
		if ($success) {
			// Set event and event Pid for registration
			$registration->setEvent($event);
			$registration->setPid($event->getPid());
			$this->registrationRepository->add($registration);

			$message = LocalizationUtility::translate('event.message.registrationsuccessfull', 'SfEventMgt');
		}

		$this->view->assign('message', $message);
		$this->view->assign('success', $success);
	}
}