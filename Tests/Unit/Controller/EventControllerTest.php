<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DateInterval;
use DateTime;
use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository;
use DERHANSEN\SfEventMgt\Event\EventPidCheckFailedEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCancelRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyConfirmRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyListViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifySearchViewVariablesEvent;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use DERHANSEN\SfEventMgt\Service\EventCacheService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Fluid\View\TemplateView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventControllerTest extends UnitTestCase
{
    protected EventController&MockObject $subject;
    protected TypoScriptFrontendController&MockObject $tsfe;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            EventController::class,
            [
                'redirect',
                'forward',
                'addFlashMessage',
                'overwriteEventDemandObject',
                'getSysLanguageUid',
                'persistAll',
                'htmlResponse',
            ],
            [],
            '',
            false
        );
        $this->tsfe = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            [],
            [],
            '',
            false
        );
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if overwriteDemand ignores properties in $ignoredSettingsForOverwriteDemand
     *
     * @test
     */
    public function overwriteDemandObjectIgnoresIgnoredProperties()
    {
        $demand = new EventDemand();
        $overwriteDemand = ['storagePage' => 1, 'category' => 1];

        $mockController = $this->getAccessibleMock(
            EventController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        self::assertEmpty($resultDemand->getStoragePage());
    }

    /**
     * Test if overwriteDemand sets a property in the given demand
     *
     * @test
     */
    public function overwriteDemandObjectSetsCategoryProperty()
    {
        $demand = new EventDemand();
        $overwriteDemand = ['category' => 1];

        $mockController = $this->getAccessibleMock(
            EventController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        self::assertSame('1', $resultDemand->getCategory());
    }

    /**
     * @test
     */
    public function initializeSaveRegistrationActionSetsDateFormat()
    {
        $settings = [
            'registration' => [
                'formatDateOfBirth' => 'd.m.Y',
            ],
        ];

        $mockPropertyMapperConfig = $this->getMockBuilder(MvcPropertyMappingConfiguration::class)->getMock();
        $mockPropertyMapperConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(DateTimeConverter::class),
            self::equalTo('dateFormat'),
            self::equalTo('d.m.Y')
        );

        $mockDateOfBirthPmConfig = $this->getMockBuilder(MvcPropertyMappingConfiguration::class)->getMock();
        $mockDateOfBirthPmConfig->expects(self::once())->method('forProperty')->with('dateOfBirth')->willReturn(
            $mockPropertyMapperConfig
        );

        $mockRegistrationArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationArgument->expects(self::once())->method('getPropertyMappingConfiguration')->willReturn(
            $mockDateOfBirthPmConfig
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockArguments->expects(self::any())->method('getArgument')->with('registration')->willReturn(
            $mockRegistrationArgument
        );

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->expects(self::any())->method('getArguments')->willReturn([]);

        $this->subject->_set('request', $mockRequest);
        $this->subject->_set('arguments', $mockArguments);
        $this->subject->_set('settings', $settings);
        $this->subject->initializeSaveRegistrationAction();
    }

    /**
     * @test
     */
    public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::any())->method('hasArgument')->willReturn(false);
        $this->subject->_set('request', $request);

        $demand = new EventDemand();
        $allEvents = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allCategories = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allLocations = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allOrganisators = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allSpeakers = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();

        $settings = [
            'pagination' => [],
        ];
        $this->subject->_set('settings', $settings);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->subject->injectEventCacheService($eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => [],
            'eventDemand' => $demand,
            'pagination' => [],
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionOverridesDemandAndFetchesAllEventsFromRepositoryAndAssignsThemToView()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::any())->method('hasArgument')->willReturn(false);
        $this->subject->_set('request', $request);

        $allEvents = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allCategories = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allLocations = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allOrganisators = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allSpeakers = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $overrideDemand = ['category' => 10];

        $settings = [
            'pagination' => [],
        ];
        $this->subject->_set('settings', $settings);

        $eventDemand = new EventDemand();
        $this->subject->expects(self::once())->method('overwriteEventDemandObject')->willReturn($eventDemand);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->subject->injectEventCacheService($eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand,
            'pagination' => [],
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     */
    public function listActionDoesNotOverrideDemandIfDisabled()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::any())->method('hasArgument')->willReturn(false);
        $this->subject->_set('request', $request);

        $allEvents = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allCategories = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allLocations = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allOrganisators = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();
        $allSpeakers = $this->getMockBuilder(QueryResult::class)->disableOriginalConstructor()->getMock();

        $overrideDemand = ['category' => 10];

        $settings = ['disableOverrideDemand' => 1, 'pagination' => []];
        $this->subject->_set('settings', $settings);

        // Ensure overwriteDemand is not called
        $eventDemand = new EventDemand();
        $this->subject->expects(self::never())->method('overwriteEventDemandObject');

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->subject->injectEventCacheService($eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand,
            'pagination' => [],
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     */
    public function detailActionAssignsEventToView()
    {
        $event = new Event();

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(['event' => $event]);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['addCacheTagsByEventRecords'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addCacheTagsByEventRecords');
        $this->subject->injectEventCacheService($eventCacheService);

        $this->subject->detailAction($event);
    }

    /**
     * @test
     */
    public function registrationActionAssignsEventToView()
    {
        $event = new Event();

        $mockPaymentService = $this->getMockBuilder(PaymentService::class)->getMock();
        $mockPaymentService->expects(self::once())->method('getPaymentMethods')->willReturn(['invoice']);
        $this->subject->injectPaymentService($mockPaymentService);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'event' => $event,
            'paymentMethods' => ['invoice'],
        ]);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->registrationAction($event);
    }

    /**
     * @test
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(false);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_NOT_ENABLED, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDeadlineExpired()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $event = $this->getMockBuilder(Event::class)->getMock();
        $deadline = new DateTime();
        $deadline->add(DateInterval::createFromDateString('yesterday'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::any())->method('getRegistrationDeadline')->willReturn($deadline);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationActionRedirectsWithMessageIfEventExpired()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('yesterday'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationRedirectsWithMessageIfMaxParticipantsReached()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::once())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::once())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsGreaterThanRemainingPlaces()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(11);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getFreePlaces')->willReturn(10);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(20);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registrationService = new RegistrationService();
        $this->subject->injectRegistrationService($registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(6);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getFreePlaces')->willReturn(10);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(20);
        $event->expects(self::once())->method('getMaxRegistrationsPerUser')->willReturn(5);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationRedirectsWithMessageIfUniqueEmailCheckEnabledAndEmailAlreadyRegistered()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)->onlyMethods(['emailNotUnique'])->getMock();
        $mockRegistrationService->expects(self::once())->method('emailNotUnique')->willReturn(true);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $repoRegistrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $repoRegistrations->expects(self::any())->method('count')->willReturn(10);

        // Inject mock of registrationRepository to registrationService
        $registrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->addMethods(['findEventRegistrationsByEmail'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::any())->method('findEventRegistrationsByEmail')->willReturn($repoRegistrations);
        $mockRegistrationService->injectRegistrationRepository($registrationRepository);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getEmail')->willReturn('email@domain.tld');

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getUniqueEmailCheck')->willReturn(true);
        $event->expects(self::any())->method('getUid')->willReturn(1);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with no autoConfirmation saves the
     * registration if maxParticipants is reached and waitlist is enabled
     *
     * @test
     */
    public function saveRegistrationActionWithoutAutoConfirmationAndWaitlistRedirectsWithMessageIfRegistrationSuccessful()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkRegistrationSuccess'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST]);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(1);
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->subject->injectRegistrationRepository($registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->_set('settings', []);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with no autoConfirmation saves the
     * registration and redirects to the saveRegistrationResult action.
     *
     * @test
     */
    public function saveRegistrationActionWithoutAutoConfirmationRedirectsWithMessageIfRegistrationSuccessful()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(1);
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->subject->injectRegistrationRepository($registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->_set('settings', []);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with autoConfirmation (settings) saves the
     * registration and redirects to the confirmationRegistration action.
     *
     * @test
     */
    public function saveRegistrationWithSettingAutoConfirmationActionRedirectsToConfirmationWithMessage()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(1);
        $registration->expects(self::any())->method('getUid')->willReturn($regUid);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->subject->injectRegistrationRepository($registrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn($regHmac);
        $this->subject->injectHashService($hashService);

        // Inject settings so autoconfirmation is disabled
        $settings = [
            'registration' => [
                'autoConfirmation' => 1,
            ],
        ];
        $this->subject->_set('settings', $settings);

        $this->subject->expects(self::once())->method('redirect')->with(
            'confirmRegistration',
            null,
            null,
            ['reguid' => $regUid, 'hmac' => $regHmac]
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with autoConfirmation (in event) saves the
     * registration and redirects to the confirmationRegistration action.
     *
     * @test
     */
    public function saveRegistrationWithEventAutoConfirmationActionRedirectsToConfirmationWithMessage()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(1);
        $registration->expects(self::any())->method('getUid')->willReturn($regUid);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getRegistration')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);
        $event->expects(self::any())->method('getEnableAutoconfirm')->willReturn(true);
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->subject->injectRegistrationRepository($registrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn($regHmac);
        $this->subject->injectHashService($hashService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'confirmRegistration',
            null,
            null,
            ['reguid' => $regUid, 'hmac' => $regHmac]
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action creates multiple registrations
     * if getAmountOfRegistrations > 1
     *
     * @test
     */
    public function saveRegistrationCreatesMultipleRegistrationIfAmountOfRegistrationsGreaterThanOne()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('request', $request);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->subject->injectHashService($hashService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(2);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new DateTime();
        $startdate->add(DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getRegistration')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);
        $event->expects(self::any())->method('getFreePlaces')->willReturn(10);
        $event->expects(self::any())->method('getMaxRegistrationsPerUser')->willReturn(2);
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->subject->injectRegistrationRepository($registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkRegistrationSuccess', 'createDependingRegistrations'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $mockRegistrationService->expects(self::once())->method('createDependingRegistrations');
        $this->subject->injectRegistrationService($mockRegistrationService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->_set('settings', []);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfWrongHmacGiven()
    {
        $eventUid = 1;
        $hmac = 'wrongmac';

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('validateHmac')->willReturn(false);
        $this->subject->injectHashService($hashService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::never())->method('findByUid')->with(1);
        $this->subject->injectEventRepository($eventRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.registrationsuccessfulwrongeventhmac',
            'titleKey' => 'registrationResult.title.failed',
            'event' => null,
        ]);
        $this->subject->_set('view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED, $eventUid, $hmac);
    }

    /**
     * Data provider for invalid emails
     *
     * @return array
     */
    public function invalidEmailsDataProvider()
    {
        return [
            'EventExpired' => [
                RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED,
                1,
                'somehmac',
                'event.message.registrationfailedeventexpired',
                'registrationResult.title.failed',
            ],
            'RegistrationDeadlineExpired' => [
                RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED,
                1,
                'somehmac',
                'event.message.registrationfaileddeadlineexpired',
                'registrationResult.title.failed',
            ],
            'EventFull' => [
                RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS,
                1,
                'somehmac',
                'event.message.registrationfailedmaxparticipants',
                'registrationResult.title.failed',
            ],
            'RegistrationSuccessful' => [
                RegistrationResult::REGISTRATION_SUCCESSFUL,
                1,
                'somehmac',
                'event.message.registrationsuccessful',
                'registrationResult.title.successful',
            ],
            'RegistrationNotEnabled' => [
                RegistrationResult::REGISTRATION_NOT_ENABLED,
                1,
                'somehmac',
                'event.message.registrationfailednotenabled',
                'registrationResult.title.failed',
            ],
            'NotEnoughFreePlaces' => [
                RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES,
                1,
                'somehmac',
                'event.message.registrationfailednotenoughfreeplaces',
                'registrationResult.title.failed',
            ],
            'MaxAmountRegistrationsExceeded' => [
                RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED,
                1,
                'somehmac',
                'event.message.registrationfailedmaxamountregistrationsexceeded',
                'registrationResult.title.failed',
            ],
            'EmailNotUnique' => [
                RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE,
                1,
                'somehmac',
                'event.message.registrationfailedemailnotunique',
                'registrationResult.title.failed',
            ],
            'UnknownResult' => [
                -1,
                1,
                'somehmac',
                '',
                '',
            ],
        ];
    }

    /**
     * Test if expected messsage is returned for saveRegistrationResult
     *
     * @dataProvider invalidEmailsDataProvider
     * @test
     * @param mixed $result
     * @param mixed $eventUid
     * @param mixed $hmac
     * @param mixed $message
     * @param mixed $title
     */
    public function saveRegistrationResultActionShowsExpectedMessage($result, $eventUid, $hmac, $message, $title)
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('validateHmac')->with('event-' . $eventUid, $hmac)
            ->willReturn(true);
        $this->subject->injectHashService($hashService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::any())->method('findByUid')->with($eventUid);
        $this->subject->injectEventRepository($eventRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => $message,
            'titleKey' => $title,
            'event' => null,
        ]);
        $this->subject->_set('view', $view);

        $this->subject->saveRegistrationResultAction($result, $eventUid, $hmac);
    }

    /**
     * Test if expected message is shown if checkConfirmRegistration fails
     *
     * @test
     */
    public function confirmRegistrationActionShowsExpectedMessageIfCheckConfirmRegistrationFailed()
    {
        $variables = [
            'messageKey' => 'event.message.confirmation_failed_wrong_hmac',
            'titleKey' => 'confirmRegistration.title.failed',
            'event' => null,
            'registration' => null,
            'failed' => true,
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed',
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkConfirmRegistration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')
            ->willReturn($returnedArray);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyConfirmRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->confirmRegistrationAction(1, 'INVALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkConfirmRegistration succeeds.
     * Also checks, if messages are sent and if registration gets confirmed.
     *
     * @test
     */
    public function confirmRegistrationActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $event = new Event();

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('setConfirmed')->with(true);
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($event);
        $mockRegistration->expects(self::once())->method('getAmountOfRegistrations')->willReturn(2);

        $variables = [
            'messageKey' => 'event.message.confirmation_successful',
            'titleKey' => 'confirmRegistration.title.successful',
            'event' => $event,
            'registration' => $mockRegistration,
            'failed' => false,
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_successful',
            'confirmRegistration.title.successful',
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')
            ->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('confirmDependingRegistrations')
            ->with($mockRegistration);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('update');
        $this->subject->injectRegistrationRepository($mockRegistrationRepository);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->_set('settings', []);

        $this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkConfirmRegistration succeeds for waitist registrations
     * Also checks, if messages are sent and if registration gets confirmed.
     *
     * @test
     */
    public function confirmRegistrationWaitlistActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $event = new Event();

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('setConfirmed')->with(true);
        $mockRegistration->expects(self::once())->method('getAmountOfRegistrations')->willReturn(2);
        $mockRegistration->expects(self::any())->method('getWaitlist')->willReturn(true);
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($event);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.confirmation_waitlist_successful',
            'titleKey' => 'confirmRegistrationWaitlist.title.successful',
            'event' => $event,
            'registration' => $mockRegistration,
            'failed' => false,
        ]);
        $this->subject->_set('view', $view);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_waitlist_successful',
            'confirmRegistrationWaitlist.title.successful',
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('confirmDependingRegistrations')->with($mockRegistration);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('update');
        $this->subject->injectRegistrationRepository($mockRegistrationRepository);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);
        $this->subject->_set('settings', []);

        $this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkCancelRegistration fails
     *
     * @test
     */
    public function cancelRegistrationActionShowsMessageIfCheckCancelRegistrationFailed()
    {
        $variables = [
            'messageKey' => 'event.message.cancel_failed_wrong_hmac',
            'titleKey' => 'cancelRegistration.title.failed',
            'event' => null,
            'failed' => true,
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed',
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkCancelRegistration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkCancelRegistration')->willReturn($returnedArray);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyCancelRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->cancelRegistrationAction(1, 'INVALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkCancelRegistration succeeds.
     * Also checks, if messages are sent and if registration gets removed.
     *
     * @test
     */
    public function cancelRegistrationActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getUid')->willReturn(1);
        $mockEvent->expects(self::any())->method('getPid')->willReturn(1);
        $mockEvent->expects(self::any())->method('getEnableCancel')->willReturn(true);
        $mockEvent->expects(self::any())->method('getCancelDeadline')->willReturn(new DateTime('yesterday'));

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(2);
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.cancel_successful',
            'cancelRegistration.title.successful',
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->onlyMethods(['checkCancelRegistration', 'cancelDependingRegistrations'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkCancelRegistration')->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('cancelDependingRegistrations')->with($mockRegistration);
        $this->subject->injectRegistrationService($mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->subject->injectNotificationService($mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('remove');
        $this->subject->injectRegistrationRepository($mockRegistrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->subject->injectEventCacheService($eventCacheService);

        $variables = [
            'messageKey' => 'event.message.cancel_successful',
            'titleKey' => 'cancelRegistration.title.successful',
            'event' => $mockEvent,
            'failed' => false,
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->_set('settings', []);
        $this->subject->cancelRegistrationAction(1, 'VALID-HMAC');
    }

    /**
     * Returns the argument mock-object required for initializeSearchAction tests
     *
     * @param string $settingsSearchDateFormat Settings for searchDateFormat
     *
     * @return mixed
     */
    protected function getInitializeSearchActionArgumentMock($settingsSearchDateFormat = null)
    {
        $mockPropertyMapperConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockPropertyMapperConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(DateTimeConverter::class),
            self::equalTo('dateFormat'),
            self::equalTo($settingsSearchDateFormat)
        );

        $mockSearchDemandPmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockSearchDemandPmConfig->expects(self::any())->method('allowAllProperties');
        $mockSearchDemandPmConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(PersistentObjectConverter::class),
            self::equalTo(PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED),
            self::equalTo(true)
        );

        $mockDatePmConfig = $this->getMockBuilder(
            MvcPropertyMappingConfiguration::class
        )->getMock();
        $mockDatePmConfig->expects(self::any())->method('forProperty')->willReturn(
            $mockPropertyMapperConfig
        );

        $mockSearchDemandArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockSearchDemandArgument->expects(self::any())->method('getPropertyMappingConfiguration')->willReturn(
            $mockSearchDemandPmConfig
        );

        $mockDateArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockDateArgument->expects(self::any())->method('getPropertyMappingConfiguration')->willReturn(
            $mockDatePmConfig
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockArguments->expects(self::any())->method('getArgument')->with('searchDemand')->willReturn(
            $mockDateArgument
        );

        return $mockArguments;
    }

    /**
     * @test
     */
    public function initializeSearchActionSetsDateFormat()
    {
        $settings = [
            'search' => [
                'dateFormat' => 'Y-m-d',
            ],
        ];

        $this->subject->_set('arguments', $this->getInitializeSearchActionArgumentMock('Y-m-d'));
        $this->subject->_set('settings', $settings);
        $this->subject->initializeSearchAction();
    }

    /**
     * @test
     */
    public function searchActionFetchesAllEventsFromRepositoryAndAssignsThemToViewForNoSearchDemand()
    {
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $settings = ['settings'];
        $this->subject->_set('settings', $settings);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'searchDemand' => null,
            'overwriteDemand' => [],
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifySearchViewVariablesEvent(
                $variables,
                $this->subject
            )
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction();
    }

    /**
     * @test
     */
    public function searchActionFetchesAllEventsFromRepositoryAndAssignsThemToViewWithSearchDemand()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $settings = ['settings'];
        $this->subject->_set('settings', $settings);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'searchDemand' => $searchDemand,
            'overwriteDemand' => [],
        ];

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifySearchViewVariablesEvent(
                $variables,
                $this->subject
            )
        );
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     */
    public function searchActionSetsSearchDemandFieldsIfSearchDemandGiven()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $settings = ['settings'];
        $this->subject->_set('settings', $settings);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     */
    public function searchActionSetsAdjustsDateFieldsIfAdjustDateSettingSetAndDateFieldsGiven()
    {
        $mockStartDate = $this->getMockBuilder('\DateTime')->getMock();
        $mockStartDate->expects(self::once())->method('setTime')->with(0, 0, 0);

        $mockEndDate = $this->getMockBuilder('\DateTime')->getMock();
        $mockEndDate->expects(self::once())->method('setTime')->with(23, 59, 59);

        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');
        $searchDemand->expects(self::any())->method('getStartDate')->willReturn($mockStartDate);
        $searchDemand->expects(self::any())->method('getEndDate')->willReturn($mockEndDate);

        $settings = [
            'search' => [
                'adjustTime' => 1,
            ],
        ];
        $this->subject->_set('settings', $settings);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(
            LocationRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     */
    public function searchActionOverwritesDemandFieldsIfOverwriteDemandObjectGiven()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $overrideDemand = ['category' => 10];
        $this->subject->expects(self::once())->method('overwriteEventDemandObject')->willReturn(new EventDemand());

        $settings = ['disableOverrideDemand' => 0];
        $this->subject->_set('settings', $settings);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     */
    public function searchActionDoesNotOverridesDemandIfOverwriteDemandDisabled()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $overrideDemand = ['category' => 10];
        $this->subject->expects(self::never())->method('overwriteEventDemandObject');

        $settings = ['disableOverrideDemand' => 1];
        $this->subject->_set('settings', $settings);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->subject->injectEventRepository($eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->subject->injectCategoryRepository($categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->subject->injectLocationRepository($locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->subject->injectOrganisatorRepository($organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->onlyMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->subject->injectSpeakerRepository($speakerRepository);

        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     */
    public function detailActionShowsEventIfEventGiven()
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $view = $this->getMockBuilder(TemplateView::class)->disableOriginalConstructor()->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(['event' => $mockEvent]);
        $this->subject->_set('view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->subject->injectEventDispatcher($eventDispatcher);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->onlyMethods(['addCacheTagsByEventRecords'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addCacheTagsByEventRecords');
        $this->subject->injectEventCacheService($eventCacheService);

        $this->subject->detailAction($mockEvent);
    }

    /**
     * @test
     */
    public function handleEventNotFoundIsSkippedWhenNoSetting(): void
    {
        $settings = [
            'event' => [
                'errorHandling' => '',
            ],
        ];

        $this->expectExceptionCode(1671205677);
        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     */
    public function handleEventNotFoundShows404Page()
    {
        $settings = [
            'event' => [
                'errorHandling' => 'pageNotFoundHandler',
            ],
        ];

        $GLOBALS['LANG'] = $this->createMock(LanguageService::class);

        $serverRequest = (new ServerRequest())->withAttribute('extbase', new ExtbaseRequestParameters());
        $request = new Request($serverRequest);

        $this->expectExceptionCode(1631261423);
        $this->expectException(PropagateResponseException::class);
        $mock = $this->getAccessibleMock(EventController::class, ['dummy']);
        $mock->_set('request', $request);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     */
    public function handleEventNotFoundRedirectsToListView()
    {
        $settings = [
            'listPid' => 100,
            'event' => [
                'errorHandling' => 'redirectToListView',
            ],
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->expects(self::once())->method('redirect')->with('list', null, null, null, 100);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     */
    public function handleEventNotFoundRedirectsToPid1IfNoListPidDefinied()
    {
        $settings = [
            'event' => [
                'errorHandling' => 'redirectToListView',
            ],
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->expects(self::once())->method('redirect')->with('list', null, null, null, 1);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     */
    public function changeEventDemandToFullMonthDateRangeAppliesExpectedDatesAndUnsetsMonthAndYear()
    {
        $calendarDateRangeResult = [
            'firstDayOfMonth' => strtotime('01.01.2017'),
            'lastDayOfMonth' => strtotime('31.01.2017'),
            'firstDayOfCalendar' => strtotime('26.12.2016'),
            'lastDayOfCalendar' => strtotime('05.02.2017'),
        ];

        $eventDemand = new EventDemand();
        $eventDemand->setYear(2017);
        $eventDemand->setMonth(1);

        $mockController = $this->getAccessibleMock(
            EventController::class,
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );

        $calendarService = $this->getMockBuilder(CalendarService::class)
            ->onlyMethods(['getCalendarDateRange'])
            ->disableOriginalConstructor()
            ->getMock();
        $calendarService->expects(self::once())->method('getCalendarDateRange')->willReturn($calendarDateRangeResult);
        $mockController->injectCalendarService($calendarService);

        $resultDemand = $mockController->_call('changeEventDemandToFullMonthDateRange', $eventDemand);
        self::assertEquals(0, $resultDemand->getMonth());
        self::assertEquals(0, $resultDemand->getYear());
        self::assertSame('26.12.2016 00:00:00', $resultDemand->getSearchDemand()->getStartDate()->format('d.m.Y H:i:s'));
        self::assertSame('05.02.2017 23:59:59', $resultDemand->getSearchDemand()->getEndDate()->format('d.m.Y H:i:s'));
    }

    /**
     * @test
     */
    public function checkPidOfEventRecordWorks()
    {
        $mockedController = $this->getAccessibleMock(EventController::class, ['dummy']);

        $event = new Event();

        // No startingpoint
        $mockedController->_set('settings', ['storagePage' => '']);
        $event->setPid(12);

        self::assertEquals($event, $mockedController->_call('checkPidOfEventRecord', $event));

        // startingpoint defined
        $mockedController->_set('settings', ['storagePage' => '1,2,123,456']);
        $event->setPid(123);

        self::assertEquals($event, $mockedController->_call('checkPidOfEventRecord', $event));

        // startingpoint is different
        $mockedController->_set('settings', ['storagePage' => '123,456']);
        $event->setPid(12);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')
            ->with(new EventPidCheckFailedEvent($event, $mockedController));
        $mockedController->_set('eventDispatcher', $eventDispatcher);

        self::assertNull($mockedController->_call('checkPidOfEventRecord', $event));
    }

    /**
     * @test
     */
    public function evaluateSingleEventSettingIsWorking()
    {
        $mockedController = $this->getAccessibleMock(EventController::class, ['dummy']);
        // singleEvent setting not configured not configured
        $mockedController->_set('settings', ['singleEvent' => null]);
        self::assertNull($mockedController->_call('evaluateSingleEventSetting', null));

        // isShortcut is configured
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();

        $mockEventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockEventRepository->expects(self::once())->method('findByUid')->with(123)
            ->willReturn($mockEvent);
        $mockedController->_set('eventRepository', $mockEventRepository);

        $mockedController->_set('settings', ['singleEvent' => 123]);
        self::assertEquals($mockEvent, $mockedController->_call('evaluateSingleEventSetting', null));
    }

    /**
     * @test
     */
    public function evaluateIsShortcutSettingIsWorking()
    {
        $mockedController = $this->getAccessibleMock(EventController::class, ['dummy']);

        // isShortcut not configured
        $mockedController->_set('settings', ['detail' => ['isShortcut' => 0]]);
        self::assertNull($mockedController->_call('evaluateIsShortcutSetting', null));

        // isShortcut is configured
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();

        $mockContentObjectRenderer = $this->getAccessibleMock(ContentObjectRenderer::class, ['dummy']);
        $mockContentObjectRenderer->_set('data', ['uid' => 123]);

        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->onlyMethods(['getContentObject'])->disableOriginalConstructor()->getMock();
        $mockConfigurationManager->expects(self::once())->method('getContentObject')
            ->willReturn($mockContentObjectRenderer);
        $mockedController->_set('configurationManager', $mockConfigurationManager);

        $mockEventRepository = $this->getMockBuilder(EventRepository::class)
            ->onlyMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockEventRepository->expects(self::once())->method('findByUid')->with(123)
            ->willReturn($mockEvent);
        $mockedController->_set('eventRepository', $mockEventRepository);

        $mockedController->_set('settings', ['detail' => ['isShortcut' => 1]]);
        self::assertEquals($mockEvent, $mockedController->_call('evaluateIsShortcutSetting', null));
    }
}
