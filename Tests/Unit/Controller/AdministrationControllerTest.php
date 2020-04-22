<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\AdministrationController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\MaintenanceService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\AdministrationController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class AdministrationControllerTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Controller\AdministrationController
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            AdministrationController::class,
            ['redirect', 'forward', 'addFlashMessage', 'redirectToUri', 'getLanguageService'],
            [],
            '',
            false
        );
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function initializeActionAssignsPid()
    {
        $this->subject->_set('pid', 1);
        $this->subject->initializeAction();
        self::assertSame(0, $this->subject->_get('pid'));
    }

    /**
     * @test
     */
    public function listActionDoesNotFetchEventsForStoragePidZero()
    {
        $this->subject->_set('pid', 0);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with(null);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $objectManager->expects(self::any())->method('get')->willReturn($demand);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::any())->method('getSessionDataByKey');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();

        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 0,
            'events' => $allEvents,
            'searchDemand' => null,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => null
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionDoesNotFetchEventsForStoragePidZeroAndDemand()
    {
        $this->subject->_set('pid', 0);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($demand);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 0,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => []
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionFetchesAllEventsForGivenStoragePidAndAssignsThemToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand', 'setStoragePage'])
            ->getMock();
        $demand->expects(self::any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects(self::any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($demand);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => []
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionUsesOverwriteDemandArrayAndAssignsItToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $demand = $this->getMockBuilder(EventDemand::class)->getMock();
        $demand->expects(self::any())->method('setSearchDemand')->with($searchDemand);
        $demand->expects(self::any())->method('setStoragePage')->with(1);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($demand);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->inject($this->subject, 'beUserSessionService', $beUserSessionService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();

        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => ['orderDirection' => 'desc']
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($searchDemand, ['orderDirection' => 'desc']);
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
        $mockPropertyMapperConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(DateTimeConverter::class),
            self::equalTo('dateFormat'),
            self::equalTo($settingsSearchDateFormat)
        );

        $mockStartDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockStartDatePmConfig->expects(self::once())->method('forProperty')->with('startDate')->willReturn(
            $mockPropertyMapperConfig
        );
        $mockEndDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockEndDatePmConfig->expects(self::once())->method('forProperty')->with('endDate')->willReturn(
            $mockPropertyMapperConfig
        );

        $mockStartDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockStartDateArgument->expects(self::once())->method('getPropertyMappingConfiguration')->willReturn(
            $mockStartDatePmConfig
        );
        $mockEndDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEndDateArgument->expects(self::once())->method('getPropertyMappingConfiguration')->willReturn(
            $mockEndDatePmConfig
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)->getMock();
        $mockArguments->expects(self::at(0))->method('getArgument')->with('searchDemand')->willReturn(
            $mockStartDateArgument
        );
        $mockArguments->expects(self::at(1))->method('getArgument')->with('searchDemand')->willReturn(
            $mockEndDateArgument
        );

        return $mockArguments;
    }

    /**
     * @test
     */
    public function initializeListActionRedirectsToErrorPageIfNoSettingsFound()
    {
        $this->subject->_set('arguments', $this->getInitializeListActionArgumentMock());
        $this->subject->expects(self::once())->method('redirect')->with('settingsError');
        $this->subject->initializeListAction();
    }

    /**
     * @test
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
     */
    public function handleExpiredRegistrationsCallsServiceAndRedirectsToListView()
    {
        $mockMaintenanceService = $this->getMockBuilder(MaintenanceService::class)
            ->getMock();
        $mockMaintenanceService->expects(self::once())->method('handleExpiredRegistrations');
        $this->inject($this->subject, 'maintenanceService', $mockMaintenanceService);

        $this->subject->expects(self::once())->method('redirect');
        $this->subject->handleExpiredRegistrationsAction();
    }

    /**
     * @test
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
        $mockLogRepo->expects(self::once())->method('findByEvent')->willReturn(
            $logEntries
        );
        $this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

        $mockSettingsService = $this->getMockBuilder(SettingsService::class)->getMock();
        $mockSettingsService->expects(self::once())->method('getCustomNotifications')->willReturn(
            $customNotifications
        );
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(self::equalTo(
            ['event' => $event, 'customNotifications' => $customNotifications, 'logEntries' => $logEntries]
        ));
        $this->inject($this->subject, 'view', $view);

        $this->subject->indexNotifyAction($event);
    }

    /**
     * @test
     */
    public function notifyActionSendsNotificationsLogsAndRedirects()
    {
        $customNotifications = ['key' => 'value'];
        $event = new Event();

        $mockSettingsService = $this->getMockBuilder(SettingsService::class)->getMock();
        $mockSettingsService->expects(self::once())->method('getCustomNotifications')->willReturn(
            $customNotifications
        );
        $this->inject($this->subject, 'settingsService', $mockSettingsService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects(self::once())->method('sendCustomNotification')->willReturn(
            1
        );
        $mockNotificationService->expects(self::once())->method('createCustomNotificationLogentry');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $this->subject->expects(self::once())->method('redirect');
        $this->subject->notifyAction($event, 'customNotification');
    }
}
