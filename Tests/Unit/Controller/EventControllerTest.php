<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Test case for class SKYFILLERS\SfEventMgt\Controller\EventController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \SKYFILLERS\SfEventMgt\Controller\EventController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('SKYFILLERS\\SfEventMgt\\Controller\\EventController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView() {

		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$eventRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\EventRepository', array('findAll'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findAll')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('events', $allEvents);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfEventExpired() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('yesterday'));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationfailedeventexpired', 'SfEventMgt'));
		$view->expects($this->at(1))->method('assign')->with('success', FALSE);
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfMaxParticipantsReached() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
		$event->expects($this->once())->method('getRegistration')->will($this->returnValue(10));
		$event->expects($this->once())->method('getParticipants')->will($this->returnValue(10));

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationfailedmaxparticipants', 'SfEventMgt'));
		$view->expects($this->at(1))->method('assign')->with('success', FALSE);
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfRegistrationSuccessfull() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
		$event->expects($this->once())->method('getRegistration')->will($this->returnValue(9));
		$event->expects($this->once())->method('getParticipants')->will($this->returnValue(10));

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('add'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('add');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationsuccessfull', 'SfEventMgt'));
		$view->expects($this->at(1))->method('assign')->with('success', TRUE);
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationAction($registration, $event);
	}
}
