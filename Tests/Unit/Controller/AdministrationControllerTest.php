<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\AdministrationController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationControllerTest extends UnitTestCase
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
            ['redirect', 'forward', 'addFlashMessage', 'redirectToUri'], [], '', false);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with(null);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMock(BeUserSessionService::class, [], [], '', false);
        $beUserSessionService->expects($this->once())->method('getSessionDataByKey');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand
        ]);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMock(BeUserSessionService::class, [], [], '', false);
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand
        ]);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            ['setSearchDemand', 'setStoragePage'], [], '', false);
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMock(BeUserSessionService::class, [], [], '', false);
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand
        ]);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            [], [], '', false);
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMock(BeUserSessionService::class, [], [], '', false);
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');

        $view->expects($this->once())->method('assignMultiple')->with([
            'showMessage' => true,
            'messageTitleKey' => 'administration.message-123.title',
            'messageContentKey' => 'administration.message-123.content',
            'events' => $allEvents,
            'searchDemand' => $searchDemand
        ]);
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
            [], [], '', false);
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
            $this->equalTo('dateFormat'),
            $this->equalTo($settingsSearchDateFormat)
        );

        $mockStartDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            [], [], '', false);
        $mockStartDatePmConfig->expects($this->once())->method('forProperty')->with('startDate')->will(
            $this->returnValue($mockPropertyMapperConfig));
        $mockEndDatePmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            [], [], '', false);
        $mockEndDatePmConfig->expects($this->once())->method('forProperty')->with('endDate')->will(
            $this->returnValue($mockPropertyMapperConfig));

        $mockStartDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            [], [], '', false);
        $mockStartDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockStartDatePmConfig));
        $mockEndDateArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            [], [], '', false);
        $mockEndDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockEndDatePmConfig));

        $mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
            [], [], '', false);
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
        $settings = [
            'search' => [
                'dateFormat' => 'd.m.Y'
            ]
        ];

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
        $settings = [
            'csvExport' =>
                ['some settings']
        ];
        $exportService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ExportService',
            [], [], '', false);
        $exportService->expects($this->once())->method('downloadRegistrationsCsv')->with(
            $this->equalTo(1),
            $this->equalTo(['some settings'])
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
            ['handleExpiredRegistrations'], [], '', false);
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
        $customNotifications = ['key' => 'value'];
        $logEntries = ['SomeResult'];
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockLogRepo = $this->getMock(CustomNotificationLogRepository::class, ['findByEvent'], [], '', false);
        $mockLogRepo->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($logEntries));
        $this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

        $mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
            ['getCustomNotifications'], [], '', false);
        $mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
            $this->returnValue($customNotifications));
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with($this->equalTo(
            ['event' => $event, 'customNotifications' => $customNotifications, 'logEntries' => $logEntries]));
        $this->inject($this->subject, 'view', $view);

        $this->subject->indexNotifyAction($event);
    }

    /**
     * @test
     * @return void
     */
    public function notifyActionSendsNotificationsLogsAndRedirects()
    {
        $customNotifications = ['key' => 'value'];
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockSettingsService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\SettingsService',
            ['getCustomNotifications'], [], '', false);
        $mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
            $this->returnValue($customNotifications));
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            ['sendCustomNotification', 'createCustomNotificationLogentry'], [], '', false);
        $mockNotificationService->expects($this->once())->method('sendCustomNotification')->will(
            $this->returnValue(1));
        $mockNotificationService->expects($this->once())->method('createCustomNotificationLogentry');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $this->subject->expects($this->once())->method('redirect');
        $this->subject->notifyAction($event, 'customNotification');
    }
}
