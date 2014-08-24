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

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\AdministrationController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DERHANSEN\SfEventMgt\Controller\AdministrationController | \PHPUnit_Framework_MockObject_MockObject
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\AdministrationController',
			array('redirect', 'forward', 'addFlashMessage', 'redirectToUri'), array(), '', FALSE);
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
	public function initializeActionAssignsPid() {
		$this->subject->_set('pid', 1);
		$this->subject->initializeAction();
		$this->assertSame(0, $this->subject->_get('pid'));
	}

	/**
	 * @test
	 * @return void
	 */
	public function listActionFetchesEventsFromRepositoryForNoStoragePageAndAssignsThemToView() {
		$demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array('get'), array(), '', FALSE);
		$objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('events', $allEvents);
		$view->expects($this->at(1))->method('assign')->with('demand', $demand);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function listActionFetchesEventsFromRepositoryForNoStoragePageAndGivenDemandAndAssignsThemToView() {
		$demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('events', $allEvents);
		$view->expects($this->at(1))->method('assign')->with('demand', $demand);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction($demand);
	}

	/**
	 * @test
	 * @return void
	 */
	public function listActionFetchesAllEventsForGivenStoragePidAndAssignsThemToView() {
		$this->subject->_set('pid', 1);

		$demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$demand->expects($this->once())->method('setStoragePage')->with(1);

		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->at(0))->method('assign')->with('events', $allEvents);
		$view->expects($this->at(1))->method('assign')->with('demand', $demand);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction($demand);
	}

	/**
	 * @test
	 * @return void
	 */
	public function listActionAssignsMessageIfMessageIdGivenToView() {
		$this->subject->_set('pid', 1);

		$demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
			array(), array(), '', FALSE);
		$demand->expects($this->once())->method('setStoragePage')->with(1);

		$allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
			array('findDemanded'), array(), '', FALSE);
		$eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
		$this->inject($this->subject, 'eventRepository', $eventRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');

		$view->expects($this->at(0))->method('assign')->with('showMessage', TRUE);
		$view->expects($this->at(1))->method('assign')->with('messageTitleKey', 'administration.message-123.title');
		$view->expects($this->at(2))->method('assign')->with('messageContentKey', 'administration.message-123.content');
		$view->expects($this->at(3))->method('assign')->with('events', $allEvents);
		$view->expects($this->at(4))->method('assign')->with('demand', $demand);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction($demand, 123);
	}

	/**
	 * @test
	 * @return void
	 */
	public function newActionRedirectsToExpectedUrl() {
		$expected = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][0]=new&returnUrl=mod.php' .
			'%3FM%3Dweb_SfEventMgtTxSfeventmgtM1%26id%3D0%26moduleToken%3DdummyToken';
		$this->subject->expects($this->once())->method('redirectToUri')->with($expected);
		$this->subject->expects($this->any())->method('getCurrentPageUid')->will($this->returnValue(0));
		$this->subject->newEventAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function initializeListActionSetsDateFormat() {
		$settings = array(
			'search' => array(
				'dateFormat' => 'd.m.Y'
			)
		);

		$mockPropertyMapperConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
			array(), array(), '', FALSE);
		$mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
			$this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
			$this->equalTo('dateFormat'),
			$this->equalTo('d.m.Y')
		);

		$mockStartDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
			array(), array(), '', FALSE);
		$mockStartDatePmConfig->expects($this->once())->method('forProperty')->with('startDate')->will(
			$this->returnValue($mockPropertyMapperConfig));
		$mockEndDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
			array(), array(), '', FALSE);
		$mockEndDatePmConfig->expects($this->once())->method('forProperty')->with('endDate')->will(
			$this->returnValue($mockPropertyMapperConfig));

		$mockStartDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
			array(), array(), '', FALSE);
		$mockStartDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
			$this->returnValue($mockStartDatePmConfig));
		$mockEndDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
			array(), array(), '', FALSE);
		$mockEndDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
			$this->returnValue($mockEndDatePmConfig));

		$mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
			array(), array(), '', FALSE);
		$mockArguments->expects($this->at(0))->method('getArgument')->with('demand')->will(
			$this->returnValue($mockStartDateArgument));
		$mockArguments->expects($this->at(1))->method('getArgument')->with('demand')->will(
			$this->returnValue($mockEndDateArgument));

		$this->subject->_set('arguments', $mockArguments);
		$this->subject->_set('settings', $settings);
		$this->subject->initializeListAction();
	}

	/**
	 * @test1
	 * @return void
	 */
	public function exportActionCallsExportServiceDownloadRegistrationsCsv() {
		$settings = array('csvExport' =>
			array('some settings')
		);
		$exportService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ExportService',
			array(), array(), '', FALSE);
		$exportService->expects($this->once())->method('downloadRegistrationsCsv')->with(
			$this->equalTo(1),
			$this->equalTo(array('some settings'))
		);
		$this->inject($this->subject, 'exportService', $exportService);
		$this->subject->_set('settings', $settings);
		$this->subject->exportAction(1);
	}

	/**
	 * @test
	 * @return void
	 */
	public function handleExpiredRegistrationsCallsServiceAndRedirectsToListView() {
		$mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
			array('handleExpiredRegistrations'), array(), '', FALSE);
		$mockRegistrationService->expects($this->once())->method('handleExpiredRegistrations');
		$this->inject($this->subject, 'registrationService', $mockRegistrationService);

		$this->subject->expects($this->once())->method('redirect');
		$this->subject->handleExpiredRegistrationsAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function indexNotifyActionAssignsExpectedObjectsToView() {
		$customNotifications = array('key' => 'value');
		$logEntries = array('SomeResult');
		$event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

		$mockLogRepo = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CustomNotificationRepository',
			array('findByEvent'), array(), '', FALSE);
		$mockLogRepo->expects($this->once())->method('findByEvent')->will(
			$this->returnValue($logEntries));
		$this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

		$mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
			array('getCustomNotifications'), array(), '', FALSE);
		$mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
			$this->returnValue($customNotifications));
		$this->inject($this->subject, 'settingsService', $mockSettingsService);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assignMultiple')->with($this->equalTo(
			array('event' => $event, 'customNotifications' => $customNotifications, 'logEntries' => $logEntries)));
		$this->inject($this->subject, 'view', $view);

		$this->subject->indexNotifyAction($event);
	}

	/**
	 * @test
	 * @return void
	 */
	public function notifyActionSendsNotificationsLogsAndRedirects() {
		$customNotifications = array('key' => 'value');
		$event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

		$mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
			array('getCustomNotifications'), array(), '', FALSE);
		$mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
			$this->returnValue($customNotifications));
		$this->inject($this->subject, 'settingsService', $mockSettingsService);

		$mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
			array('sendCustomNotification','createCustomNotificationLogentry'), array(), '', FALSE);
		$mockNotificationService->expects($this->once())->method('sendCustomNotification')->will(
			$this->returnValue(1));
		$mockNotificationService->expects($this->once())->method('createCustomNotificationLogentry');
		$this->inject($this->subject, 'notificationService', $mockNotificationService);

		$this->subject->expects($this->once())->method('redirect');
		$this->subject->notifyAction($event, 'customNotification');
	}
}
