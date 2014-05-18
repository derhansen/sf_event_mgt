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
		// Set event and event Pid for registration
		$registration->setEvent($event);
		$registration->setPid($event->getPid());
		$this->registrationRepository->add($registration);
	}
}