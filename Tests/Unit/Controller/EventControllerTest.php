<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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

use DERHANSEN\SfEventMgt\Utility\RegistrationResult;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\EventController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DERHANSEN\SfEventMgt\Controller\EventController
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
			array('redirect', 'forward', 'addFlashMessage', 'createDemandObjectFromSettings'), array(), '', FALSE);
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
	 * @return void
	 */
	public function createDemandObjectFromSettingsWithoutCategory() {
		$mockController = $this->getMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
			array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);

		$settings = array(
			'displayMode' => 'all',
			'storagePage' => 1,
			'category' => 10,
			'topEventRestriction' => 2
		);

		$mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
		$mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
		$mockDemand->expects($this->at(2))->method('setCategory')->with(10);
		$mockDemand->expects($this->at(3))->method('setTopEventRestriction')->with(2);

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
		$this->inject($mockController, 'objectManager', $objectManager);

		$mockController->createDemandObjectFromSettings($settings);
	}

	/**
	 * @test
	 * @return void
	 */
	public function createDemandObjectFromSettingsWithCategory() {
		$mockController = $this->getMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
			array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);

		$settings = array(
			'displayMode' => 'all',
			'storagePage' => 1,
			'category' => 10,
			'topEventRestriction' => 2
		);

		$mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
		$mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
		$mockDemand->expects($this->at(2))->method('setCategory')->with(20);
		$mockDemand->expects($this->at(3))->method('setTopEventRestriction')->with(2);

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
		$this->inject($mockController, 'objectManager', $objectManager);

		$mockController->createDemandObjectFromSettings($settings, 20);
	}

	/**
	 * @test
	 * @return void
	 */
	public function initializeSaveRegistrationActionSetsDateFormat() {
		$settings = array(
			'registration' => array(
				'formatDateOfBirth' => 'd.m.Y'
			)
		);

		$mockPropertyMapperConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
			array(), array(), '', FALSE);
		$mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
			$this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
			$this->equalTo('dateFormat'),
			$this->equalTo('d.m.Y')
		);

		$mockDateOfBirthPmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
			array(), array(), '', FALSE);
		$mockDateOfBirthPmConfig->expects($this->once())->method('forProperty')->with('dateOfBirth')->will(
			$this->returnValue($mockPropertyMapperConfig));

		$mockRegistrationArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
			array(), array(), '', FALSE);
		$mockRegistrationArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
			$this->returnValue($mockDateOfBirthPmConfig));

		$mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
			array(), array(), '', FALSE);
		$mockArguments->expects($this->at(0))->method('getArgument')->with('registration')->will(
			$this->returnValue($mockRegistrationArgument));

		$this->subject->_set('arguments', $mockArguments);
		$this->subject->_set('settings', $settings);
		$this->subject->initializeSaveRegistrationAction();
	}



	/**
	 * @test
	 * @return void
	 */
	public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView() {
		$demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$category = 0;

		$settings = array('settings');
		$this->inject($this->subject, 'settings', $settings);

		$this->subject->expects($this->once())->method('createDemandObjectFromSettings')
			->with($settings)->will($this->returnValue($demand));

		$eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
			array('findAll'), array(), '', FALSE);
		$categoryRepository->expects($this->once())->method('findAll')->will($this->returnValue($allCategories));
		$this->inject($this->subject, 'categoryRepository', $categoryRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('events', $allEvents);
		$view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
		$view->expects($this->at(2))->method('assign')->with('selectedCategoryUid', $category);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function detailActionAssignsEventToView() {
		$event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('event', $event);
		$this->inject($this->subject, 'view', $view);

		$this->subject->detailAction($event);
	}

	/**
	 * @test
	 * @return void
	 */
	public function registrationActionAssignsEventToView() {
		$event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('event', $event);
		$this->inject($this->subject, 'view', $view);

		$this->subject->registrationAction($event);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled() {
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(FALSE));

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_NOT_ENABLED));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationActionRedirectsWithMessageIfRegistrationDeadlineExpired() {
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$deadline = new \DateTime();
		$deadline->add(\DateInterval::createFromDateString('yesterday'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(TRUE));
		$event->expects($this->any())->method('getRegistrationDeadline')->will($this->returnValue($deadline));

		$this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', NULL, NULL,
			array('result' => RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED));

		$this->subject->saveRegistrationAction($registration, $event);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationActionRedirectsWithMessageIfEventExpired() {
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
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
	 * @return void
	 */
	public function saveRegistrationRedirectsWithMessageIfMaxParticipantsReached() {
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$registrations->expects($this->once())->method('count')->will($this->returnValue(10));

		$event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
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
	 * @return void
	 */
	public function saveRegistrationActionRedirectsWithMessageIfRegistrationSuccessful() {
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
			array(), '', FALSE);

		$registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);
		$registrations->expects($this->once())->method('count')->will($this->returnValue(9));

		$event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', FALSE);
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(TRUE));
		$event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
		$event->expects($this->once())->method('getRegistration')->will($this->returnValue($registrations));
		$event->expects($this->once())->method('getMaxParticipants')->will($this->returnValue(10));

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('add'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('add');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
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

		$settingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService', array('getClearCacheUids'),
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
	 * @return void
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfEventExpired() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.registrationfailedeventexpired');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'registrationResult.title.failed');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationDeadlineExpired() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.registrationfaileddeadlineexpired');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'registrationResult.title.failed');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfEventFull() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.registrationfailedmaxparticipants');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'registrationResult.title.failed');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationSuccessful() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.registrationsuccessful');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'registrationResult.title.successful');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_SUCCESSFUL);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationNotEnabled() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.registrationfailednotenabled');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'registrationResult.title.failed');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_NOT_ENABLED);
	}

	/**
	 * @test
	 * @return void
	 */
	public function saveRegistrationResultActionShowsNoMessageIfUnknownResultGiven() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'');
		$this->inject($this->subject, 'view', $view);

		$this->subject->saveRegistrationResultAction(-1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfInvalidHmac() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.confirmation_failed_wrong_hmac');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'confirmRegistration.title.failed');
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'hashService', $hashService);

		$this->subject->confirmRegistrationAction(1, 'INVALID-HMAC');
	}

	/**
	 * @test
	 * @return void
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfRegistrationNotFound() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.confirmation_failed_registration_not_found');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'confirmRegistration.title.failed');
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue(NULL));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 * @return void
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationUntilExpired() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.confirmation_failed_confirmation_until_expired');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'confirmRegistration.title.failed');
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('yesterday');
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 * @return void
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationAlreadyConfirmed() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.confirmation_failed_already_confirmed');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'confirmRegistration.title.failed');
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('tomorrow');
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));
		$registration->expects($this->once())->method('getConfirmed')->will($this->returnValue(TRUE));

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}

	/**
	 * @test
	 * @return void
	 */
	public function confirmRegistrationActionShowsExpectedMessageIfConfirmationSuccessful() {
		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('messageKey',
			'event.message.confirmation_successful');
		$view->expects($this->at(1))->method('assign')->with('titleKey',
			'confirmRegistration.title.successful');
		$this->inject($this->subject, 'view', $view);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
			array('validateHmac'), array(), '', FALSE);
		$hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'hashService', $hashService);

		$expiredConfirmationDateTime = new \DateTime('tomorrow');
		$registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '', FALSE);
		$registration->expects($this->once())->method('getConfirmationUntil')->will($this->returnValue($expiredConfirmationDateTime));
		$registration->expects($this->once())->method('getConfirmed')->will($this->returnValue(FALSE));

		$registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
			array('findByUid', 'update'), array(), '', FALSE);
		$registrationRepository->expects($this->once())->method('findByUid')->will($this->returnValue($registration));
		$registrationRepository->expects($this->once())->method('update');
		$this->inject($this->subject, 'registrationRepository', $registrationRepository);

		$notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
			array(), array(), '', FALSE);
		$notificationService->expects($this->once())->method('sendUserMessage');
		$notificationService->expects($this->once())->method('sendAdminMessage');
		$this->inject($this->subject, 'notificationService', $notificationService);

		$this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
	}
}
