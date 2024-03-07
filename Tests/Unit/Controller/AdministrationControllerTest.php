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
use DERHANSEN\SfEventMgt\Event\ModifyAdministrationIndexNotifyViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyAdministrationListViewVariablesEvent;
use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\MaintenanceService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AdministrationControllerTest extends UnitTestCase
{
    protected AdministrationController $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            AdministrationController::class,
            ['redirect', 'addFlashMessage', 'redirectToUri', 'getLanguageService', 'initModuleTemplateAndReturnResponse'],
            [],
            '',
            false
        );
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function initializeActionAssignsPid(): void
    {
        $this->subject->_set('pid', 1);
        $this->subject->_set('request', $this->createMock(Request::class));
        $this->subject->initializeAction();
        self::assertSame(0, $this->subject->_get('pid'));
    }

    /**
     * @test
     */
    public function listActionDoesNotFetchEventsForStoragePidZero(): void
    {
        $this->subject->_set('settings', ['dummy' => 'settings']);
        $this->subject->_set('pid', 0);

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::any())->method('getSessionDataByKey');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $variables = [
            'pid' => 0,
            'events' => [],
            'searchDemand' => new SearchDemand(),
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => [
                'orderField' => 'title',
                'orderDirection' => 'asc',
            ],
            'pagination' => null,
        ];
        $this->subject->expects(self::once())->method('initModuleTemplateAndReturnResponse')
            ->with('Administration/List', $variables);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyAdministrationListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionDoesNotFetchEventsForStoragePidZeroAndDemand(): void
    {
        $this->subject->_set('settings', ['dummy' => 'settings']);
        $this->subject->_set('pid', 0);

        $searchDemand = new SearchDemand();

        $beUserSessionService = $this->getMockBuilder(BeUserSessionService::class)->getMock();
        $beUserSessionService->expects(self::once())->method('saveSessionData');
        $this->subject->injectBeUserSessionService($beUserSessionService);

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $variables = [
            'pid' => 0,
            'events' => [],
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => [
                'orderField' => 'title',
                'orderDirection' => 'asc',
            ],
            'pagination' => null,
        ];
        $this->subject->expects(self::once())->method('initModuleTemplateAndReturnResponse')
            ->with('Administration/List', $variables);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyAdministrationListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionFetchesAllEventsForGivenStoragePidAndPassesExpectedVariablesToTemplateView(): void
    {
        $this->subject->_set('settings', ['dummy' => 'settings']);
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

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $variables = [
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => [
                'orderField' => 'title',
                'orderDirection' => 'asc',
            ],
            'pagination' => [],
        ];
        $this->subject->expects(self::once())->method('initModuleTemplateAndReturnResponse')
            ->with('Administration/List', $variables);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyAdministrationListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction($searchDemand);
    }

    /**
     * @test
     */
    public function listActionUsesOverwriteDemandArrayAndPassesExpectedVariablesToTemplateView(): void
    {
        $this->subject->_set('settings', ['dummy' => 'settings']);
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

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $variables = [
            'pid' => 1,
            'events' => $allEvents,
            'searchDemand' => $searchDemand,
            'orderByFields' => $this->subject->getOrderByFields(),
            'orderDirections' => $this->subject->getOrderDirections(),
            'overwriteDemand' => ['orderDirection' => 'desc'],
            'pagination' => [],
        ];
        $this->subject->expects(self::once())->method('initModuleTemplateAndReturnResponse')
            ->with('Administration/List', $variables);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyAdministrationListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction($searchDemand, ['orderDirection' => 'desc']);
    }

    /**
     * @test
     */
    public function initializeListActionSetsDefaultDateFormatIfEmpty(): void
    {
        $settings = [
            'search' => [],
        ];

        $this->subject->_set('settings', $settings);
        $this->subject->initializeListAction();

        self::assertEquals('H:i d-m-Y', $this->subject->_get('settings')['search']['dateFormat']);
    }

    /**
     * @test
     */
    public function handleExpiredRegistrationsCallsServiceAndRedirectsToListView(): void
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
    public function indexNotifyActionPassesExpectedVariablesToTemplateView(): void
    {
        $this->subject->_set('settings', ['dummy' => 'settings']);
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

        $mockCustomNotification = new CustomNotification();

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(1);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $recipients = $this->subject->getNotificationRecipients();

        $variables = [
            'event' => $event,
            'customNotification' => $mockCustomNotification,
            'customNotifications' => $customNotifications,
            'logEntries' => $logEntries,
            'recipients' => $recipients,
        ];
        $this->subject->expects(self::once())->method('initModuleTemplateAndReturnResponse')
            ->with('Administration/IndexNotify', $variables);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyAdministrationIndexNotifyViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->indexNotifyAction($event);
    }

    /**
     * @test
     */
    public function notifyActionSendsNotificationsLogsAndRedirects(): void
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
    public function checkEventAccessReturnsFalseIfNoEventAccess(): void
    {
        $event = new Event();

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)->getMock();
        $mockBackendUser->expects(self::once())->method('isInWebMount')->willReturn(null);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        self::assertFalse($this->subject->checkEventAccess($event));
    }
}
