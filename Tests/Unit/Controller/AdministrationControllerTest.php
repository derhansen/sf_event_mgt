<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\AdministrationController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\MaintenanceService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\AdministrationController.
 */
class AdministrationControllerTest extends UnitTestCase
{
    use ProphecyTrait;

    /**
     * @var AdministrationController
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            AdministrationController::class,
            ['redirect', 'forward', 'addFlashMessage', 'redirectToUri', 'getLanguageService', 'initModuleTemplateAndReturnResponse'],
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

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::any())->method('getSessionDataByKey');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $requestProphecy = $this->prophesize(Request::class);
        $requestProphecy->hasArgument('operation')->willReturn(false);
        $requestProphecy->hasArgument('currentPage')->willReturn(false);
        $this->subject->_set('request', $requestProphecy->reveal());

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 0,
            'events' => [],
            'searchDemand' => new SearchDemand(),
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => null,
            'pagination' => null,
        ]);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionDoesNotFetchEventsForStoragePidZeroAndDemand()
    {
        $this->subject->_set('pid', 0);

        $searchDemand = new SearchDemand();

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $requestProphecy = $this->prophesize(Request::class);
        $requestProphecy->hasArgument('operation')->willReturn(false);
        $requestProphecy->hasArgument('currentPage')->willReturn(false);
        $this->subject->_set('request', $requestProphecy->reveal());

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 0,
            'events' => [],
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => [],
            'pagination' => null,
        ]);
        $this->subject->_set('view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionFetchesAllEventsForGivenStoragePidAndAssignsThemToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(QueryResultInterface::class)->getMock();

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $mockBackendUser->expects(self::once())->method('check')->willReturn(true);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $requestProphecy = $this->prophesize(Request::class);
        $requestProphecy->hasArgument('operation')->willReturn(false);
        $requestProphecy->hasArgument('currentPage')->willReturn(false);
        $this->subject->_set('request', $requestProphecy->reveal());

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => [],
            'pagination' => [],
        ]);
        $this->subject->_set('view', $view);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionUsesOverwriteDemandArrayAndAssignsItToView()
    {
        $this->subject->_set('pid', 1);

        $searchDemand = new SearchDemand();
        $allEvents = $this->getMockBuilder(QueryResultInterface::class)->getMock();

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $mockBackendUser->expects(self::once())->method('check')->willReturn(true);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $requestProphecy = $this->prophesize(Request::class);
        $requestProphecy->hasArgument('operation')->willReturn(false);
        $requestProphecy->hasArgument('currentPage')->willReturn(false);
        $this->subject->_set('request', $requestProphecy->reveal());

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();

        $view->expects(self::once())->method('assignMultiple')->with([
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => ['orderDirection' => 'desc'],
            'pagination' => [],
        ]);
        $this->subject->_set('view', $view);

        $this->subject->listAction($searchDemand, ['orderDirection' => 'desc']);
    }

    /**
     * Returns the argument mock-object required for initializeListAction tests
     *
     * @param string $settingsSearchDateFormat Settings for searchDateFormat
     *
     * @return mixed
     */
    protected function getInitializeListActionArgumentMock($settingsSearchDateFormat = '')
    {
        $mockPropertyMapperConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockPropertyMapperConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(DateTimeConverter::class),
            self::equalTo('dateFormat'),
            self::equalTo($settingsSearchDateFormat)
        );

        $mockDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockDatePmConfig->expects(self::any())->method('forProperty')->willReturn(
            $mockPropertyMapperConfig
        );

        $mockDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockDateArgument->expects(self::any())->method('getPropertyMappingConfiguration')->willReturn(
            $mockDatePmConfig
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)->getMock();
        $mockArguments->expects(self::any())->method('getArgument')->with('searchDemand')->willReturn(
            $mockDateArgument
        );

        return $mockArguments;
    }

    /**
     * @test
     */
    public function initializeListActionSetsDateFormat()
    {
        $settings = [
            'search' => [
                'dateFormat' => 'd.m.Y',
            ],
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
        $this->subject->injectMaintenanceService($mockMaintenanceService);

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
            ->addMethods(['findByEvent'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockLogRepo->expects(self::once())->method('findByEvent')->willReturn(
            $logEntries
        );
        $this->subject->injectCustomNotificationLogRepository($mockLogRepo);

        $mockSettingsService = $this->getMockBuilder(SettingsService::class)->getMock();
        $mockSettingsService->expects(self::once())->method('getCustomNotifications')->willReturn(
            $customNotifications
        );
        $this->subject->injectSettingsService($mockSettingsService);

        $mockCustomNotification = GeneralUtility::makeInstance(CustomNotification::class);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $recipients = $this->subject->getNotificationRecipients();

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(self::equalTo(
            [
                'event' => $event,
                'customNotification' => $mockCustomNotification,
                'customNotifications' => $customNotifications,
                'logEntries' => $logEntries,
                'recipients' => $recipients,
            ]
        ));
        $this->subject->_set('view', $view);

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
        $this->subject->injectSettingsService($mockSettingsService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $mockNotificationService->expects(self::once())->method('sendCustomNotification')->willReturn(1);
        $mockNotificationService->expects(self::once())->method('createCustomNotificationLogentry');
        $this->subject->injectNotificationService($mockNotificationService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $customNotification = new CustomNotification();
        $customNotification->setTemplate('key');

        $this->subject->_set('settings', []);
        $this->subject->expects(self::once())->method('redirect');
        $this->subject->notifyAction($event, $customNotification);
    }

    /**
     * @test
     */
    public function checkEventAccessRedirectsToListViewIfNoEventAccess()
    {
        $event = new Event();

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(null);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->expects(self::once())->method('redirect');
        $this->subject->checkEventAccess($event);
    }
}
