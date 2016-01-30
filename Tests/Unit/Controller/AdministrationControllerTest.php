<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

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

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\AdministrationController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Controller\AdministrationController | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\AdministrationController',
            array('redirect', 'forward', 'addFlashMessage', 'redirectToUri'), array(), '', false);
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     * @return void
     */
    public function initializeActionAssignsPid()
    {
        $this->subject->_set('pid', 1);
        $this->subject->initializeAction();
        $this->assertSame(0, $this->subject->_get('pid'));
    }

    /**
     * @test
     * @return void
     */
    public function listActionFetchesEventsFromRepositoryForNoStoragePageAndAssignsThemToView()
    {
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            array('setSearchDemand'), array(), '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with(null);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('searchDemand', $searchDemand);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     * @return void
     */
    public function listActionFetchesEventsFromRepositoryForNoStoragePageAndGivenDemandAndAssignsThemToView()
    {
        $searchDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            array('setSearchDemand'), array(), '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('searchDemand', $searchDemand);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     * @return void
     */
    public function listActionFetchesAllEventsForGivenStoragePidAndAssignsThemToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            array('setSearchDemand'), array(), '', false);
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('searchDemand', $searchDemand);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     * @return void
     */
    public function listActionAssignsMessageIfMessageIdGivenToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            array(), array(), '', false);
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');

        $view->expects($this->at(0))->method('assign')->with('showMessage', true);
        $view->expects($this->at(1))->method('assign')->with('messageTitleKey', 'administration.message-123.title');
        $view->expects($this->at(2))->method('assign')->with('messageContentKey', 'administration.message-123.content');
        $view->expects($this->at(3))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(4))->method('assign')->with('searchDemand', $searchDemand);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand, 123);
    }

    /**
     * Returns the argument mock-object required for initializeListAction tests
     *
     * @param string $settingsSearchDateFormat Settings for searchDateFormat
     *
     * @return mixed
     */
    protected function getInitializeListActionArgumentMock($settingsSearchDateFormat = null)
    {
        $mockPropertyMapperConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            array(), array(), '', false);
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
            $this->equalTo('dateFormat'),
            $this->equalTo($settingsSearchDateFormat)
        );

        $mockStartDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            array(), array(), '', false);
        $mockStartDatePmConfig->expects($this->once())->method('forProperty')->with('startDate')->will(
            $this->returnValue($mockPropertyMapperConfig));
        $mockEndDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            array(), array(), '', false);
        $mockEndDatePmConfig->expects($this->once())->method('forProperty')->with('endDate')->will(
            $this->returnValue($mockPropertyMapperConfig));

        $mockStartDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            array(), array(), '', false);
        $mockStartDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockStartDatePmConfig));
        $mockEndDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            array(), array(), '', false);
        $mockEndDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockEndDatePmConfig));

        $mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
            array(), array(), '', false);
        $mockArguments->expects($this->at(0))->method('getArgument')->with('searchDemand')->will(
            $this->returnValue($mockStartDateArgument));
        $mockArguments->expects($this->at(1))->method('getArgument')->with('searchDemand')->will(
            $this->returnValue($mockEndDateArgument));
        return $mockArguments;
    }

    /**
     * @test
     * @return void
     */
    public function initializeListActionRedirectsToErrorPageIfNoSettingsFound()
    {
        $this->subject->_set('arguments', $this->getInitializeListActionArgumentMock());
        $this->subject->expects($this->once())->method('redirect')->with('settingsError');
        $this->subject->initializeListAction();
    }


    /**
     * @test
     * @return void
     */
    public function initializeListActionSetsDateFormat()
    {
        $settings = array(
            'search' => array(
                'dateFormat' => 'd.m.Y'
            )
        );

        $this->subject->_set('arguments', $this->getInitializeListActionArgumentMock('d.m.Y'));
        $this->subject->_set('settings', $settings);
        $this->subject->initializeListAction();
    }

    /**
     * @test
     * @return void
     */
    public function exportActionCallsExportServiceDownloadRegistrationsCsv()
    {
        $settings = array(
            'csvExport' =>
                array('some settings')
        );
        $exportService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ExportService',
            array(), array(), '', false);
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
    public function handleExpiredRegistrationsCallsServiceAndRedirectsToListView()
    {
        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('handleExpiredRegistrations'), array(), '', false);
        $mockRegistrationService->expects($this->once())->method('handleExpiredRegistrations');
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $this->subject->expects($this->once())->method('redirect');
        $this->subject->handleExpiredRegistrationsAction();
    }

    /**
     * @test
     * @return void
     */
    public function indexNotifyActionAssignsExpectedObjectsToView()
    {
        $customNotifications = array('key' => 'value');
        $logEntries = array('SomeResult');
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockLogRepo = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CustomNotificationRepository',
            array('findByEvent'), array(), '', false);
        $mockLogRepo->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($logEntries));
        $this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

        $mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
            array('getCustomNotifications'), array(), '', false);
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
    public function notifyActionSendsNotificationsLogsAndRedirects()
    {
        $customNotifications = array('key' => 'value');
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
            array('getCustomNotifications'), array(), '', false);
        $mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
            $this->returnValue($customNotifications));
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            array('sendCustomNotification', 'createCustomNotificationLogentry'), array(), '', false);
        $mockNotificationService->expects($this->once())->method('sendCustomNotification')->will(
            $this->returnValue(1));
        $mockNotificationService->expects($this->once())->method('createCustomNotificationLogentry');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $this->subject->expects($this->once())->method('redirect');
        $this->subject->notifyAction($event, 'customNotification');
    }
}
