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

use SKYFILLERS\SfEventMgt\Utility\RegistrationResult;
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

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getMock('SKYFILLERS\\SfEventMgt\\Controller\\EventController', array('redirect', 'forward', 'addFlashMessage', 'createDemandObjectFromSettings'), array(), '', FALSE);
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView() {
		$demand = new \SKYFILLERS\SfEventMgt\Domain\Model\Dto\EventDemand();
		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$settings = array('settings');
		$this->inject($this->subject, 'settings', $settings);

		$this->subject->expects($this->once())->method('createDemandObjectFromSettings')
			->with($settings)->will($this->returnValue($demand));

		$eventRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('events', $allEvents);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfRegistrationDisabled() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(FALSE));

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_NOT_ENABLED));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfEventExpired() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('yesterday'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(TRUE));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfMaxParticipantsReached() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$registrations->expects($this->once())->method('count')->will($this->returnValue(10));

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(TRUE));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
		$event->expects($this->once())->method('getRegistration')->will($this->returnValue($registrations));
		$event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationActionAssignsExpectedObjectsToViewIfRegistrationSuccessful() {
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$registrations->expects($this->once())->method('count')->will($this->returnValue(9));

		$event = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(TRUE));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
		$event->expects($this->once())->method('getRegistration')->will($this->returnValue($registrations));
		$event->expects($this->once())->method('getMaxParticipants')->will($this->returnValue(10));

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('add'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('add');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$notificationService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\NotificationService',
			array(), array(), '', FALSE);
		$notificationService->expects($this->once())->method('sendUserMessage');
		$notificationService->expects($this->once())->method('sendAdminMessage');
		$this->inject($this->subject, 'notificationService', $notificationService);

		$persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
			array('persistAll'), array(), '', FALSE);
		$persistenceManager->expects($this->once())->method('persistAll');

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$settingsService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\SettingsService', array('getClearCacheUids'),
			array(), '', FALSE);
		$settingsService->expects($this->once())->method('getClearCacheUids')->will(
			$this->returnValue(array('0' => '1')));
		$this->inject($this->subject, 'settingsService', $settingsService);

		$cacheService = $this->getMock('TYPO3\\CMS\\Extbase\\Service\\CacheService', array('clearPageCache'),
			array(), '', FALSE);
		$cacheService->expects($this->once())->method('clearPageCache');
		$this->inject($this->subject, 'cacheService', $cacheService);

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_SUCCESSFUL));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfEventExpired() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationfailedeventexpired', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED);
	}

	/**
	 * @test
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfEventFull() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationfailedmaxparticipants', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS);
	}

	/**
	 * @test
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationSuccessful() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationsuccessful', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_SUCCESSFUL);
	}

	/**
	 * @test
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationNotEnabled() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('message',
			LocalizationUtility::translate('event.message.registrationfailednotenabled', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_NOT_ENABLED);
	}

	/**
	 * @test
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfInvalidHMAC() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('message',
			LocalizationUtility::translate('event.message.confirmation_failed_wrong_hmac', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'hashService', $hashService);

		$this->subject->confirmRegistrationAction(1, 'INVALID-HMAC');
	}

	/**
	 * @test
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfRegistrationNotFound() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('message',
			LocalizationUtility::translate('event.message.confirmation_failed_registration_not_found', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue(NULL));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationUntilExpired() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('message',
			LocalizationUtility::translate('event.message.confirmation_failed_confirmation_until_expired', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('yesterday');
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationAlreadyConfirmed() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('message',
			LocalizationUtility::translate('event.message.confirmation_failed_already_confirmed', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('tomorrow');
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));
		$registration->expects($this->once())->method('getConfirmed')->will($this->returnValue(TRUE));

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationSuccessful() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('message',
			LocalizationUtility::translate('event.message.confirmation_successful', 'SfEventMgt'));
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('tomorrow');
		$registration = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));
		$registration->expects($this->once())->method('getConfirmed')->will($this->returnValue(FALSE));

		$registrationRepository = $this->getMock('SKYFILLERS\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid', 'update'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$registrationRepository->expects($this->once())->method('update');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$notificationService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\NotificationService',
			array(), array(), '', FALSE);
		$notificationService->expects($this->once())->method('sendUserMessage');
		$notificationService->expects($this->once())->method('sendAdminMessage');
		$this->inject($this->subject, 'notificationService', $notificationService);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}
}
