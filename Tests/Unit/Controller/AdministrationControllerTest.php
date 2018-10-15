<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Controller\AdministrationController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\ExportService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;

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
        $this->subject = $this->getAccessibleMock(
            AdministrationController::class,
            ['redirect', 'forward', 'addFlashMessage', 'redirectToUri'],
            [],
            '',
            false
        );
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
        $mockBeUserAuth = $this->getMockBuilder(BackendUserAuthentication::class)
            ->setMethods(['getDefaultUploadTemporaryFolder'])
            ->getMock();
        $mockBeUserAuth->expects($this->once())->method('getDefaultUploadTemporaryFolder')
            ->will($this->returnValue(true));
        $GLOBALS['BE_USER'] = $mockBeUserAuth;

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects($this->once())->method('setSearchDemand')->with(null);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects($this->once())->method('getSessionDataByKey');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();

        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'csvExportPossible' => true
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
        $mockBeUserAuth = $this->getMockBuilder(BackendUserAuthentication::class)
            ->setMethods(['getDefaultUploadTemporaryFolder'])
            ->getMock();
        $mockBeUserAuth->expects($this->once())->method('getDefaultUploadTemporaryFolder')
            ->will($this->returnValue(true));
        $GLOBALS['BE_USER'] = $mockBeUserAuth;

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'csvExportPossible' => true
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
        $mockBeUserAuth = $this->getMockBuilder(BackendUserAuthentication::class)
            ->setMethods(['getDefaultUploadTemporaryFolder'])
            ->getMock();
        $mockBeUserAuth->expects($this->once())->method('getDefaultUploadTemporaryFolder')
            ->will($this->returnValue(true));
        $GLOBALS['BE_USER'] = $mockBeUserAuth;

        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand', 'setStoragePage'])
            ->getMock();
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'csvExportPossible' => true
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
        $mockBeUserAuth = $this->getMockBuilder(BackendUserAuthentication::class)
            ->setMethods(['getDefaultUploadTemporaryFolder'])
            ->getMock();
        $mockBeUserAuth->expects($this->once())->method('getDefaultUploadTemporaryFolder')
            ->will($this->returnValue(true));
        $GLOBALS['BE_USER'] = $mockBeUserAuth;

        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)->getMock();
        $demand->expects($this->any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects($this->any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($demand));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects($this->once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();

        $view->expects($this->once())->method('assignMultiple')->with([
            'showMessage' => true,
            'messageTitleKey' => 'administration.message-123.title',
            'messageContentKey' => 'administration.message-123.content',
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'csvExportPossible' => true
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
        $mockPropertyMapperConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo(DateTimeConverter::class),
            $this->equalTo('dateFormat'),
            $this->equalTo($settingsSearchDateFormat)
        );

        $mockStartDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockStartDatePmConfig->expects($this->once())->method('forProperty')->with('startDate')->will(
            $this->returnValue($mockPropertyMapperConfig)
        );
        $mockEndDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockEndDatePmConfig->expects($this->once())->method('forProperty')->with('endDate')->will(
            $this->returnValue($mockPropertyMapperConfig)
        );

        $mockStartDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockStartDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockStartDatePmConfig)
        );
        $mockEndDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEndDateArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockEndDatePmConfig)
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)->getMock();
        $mockArguments->expects($this->at(0))->method('getArgument')->with('searchDemand')->will(
            $this->returnValue($mockStartDateArgument)
        );
        $mockArguments->expects($this->at(1))->method('getArgument')->with('searchDemand')->will(
            $this->returnValue($mockEndDateArgument)
        );

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
            'csvExport' => ['some settings']
        ];
        $exportService = $this->getMockBuilder(ExportService::class)->getMock();
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
        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->getMock();
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
        $event = new Event();

        $mockLogRepo = $this->getMockBuilder(CustomNotificationLogRepository::class)
            ->setMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockLogRepo->expects($this->once())->method('findByEvent')->will(
            $this->returnValue($logEntries)
        );
        $this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

        $mockSettingsService = $this->getMockBuilder(SettingsService::class)->getMock();
        $mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
            $this->returnValue($customNotifications)
        );
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects($this->once())->method('assignMultiple')->with($this->equalTo(
            ['event' => $event, 'customNotifications' => $customNotifications, 'logEntries' => $logEntries]
        ));
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
        $event = new Event();

        $mockSettingsService = $this->getMockBuilder(SettingsService::class)->getMock();
        $mockSettingsService->expects($this->once())->method('getCustomNotifications')->will(
            $this->returnValue($customNotifications)
        );
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects($this->once())->method('sendCustomNotification')->will(
            $this->returnValue(1)
        );
        $mockNotificationService->expects($this->once())->method('createCustomNotificationLogentry');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $this->subject->expects($this->once())->method('redirect');
        $this->subject->notifyAction($event, 'customNotification');
    }
}
