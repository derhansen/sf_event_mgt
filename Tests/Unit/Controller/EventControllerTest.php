<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CategoryRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\SpeakerRepository;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationCancelledEvent;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationConfirmedEvent;
use DERHANSEN\SfEventMgt\Event\AfterRegistrationSavedEvent;
use DERHANSEN\SfEventMgt\Event\EventPidCheckFailedEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCancelRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyConfirmRegistrationViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifyCreateDependingRegistrationsEvent;
use DERHANSEN\SfEventMgt\Event\ModifyListViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\ModifySearchViewVariablesEvent;
use DERHANSEN\SfEventMgt\Event\WaitlistMoveUpEvent;
use DERHANSEN\SfEventMgt\Service\CalendarService;
use DERHANSEN\SfEventMgt\Service\EventCacheService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Http\ImmediateResponseException;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\ErrorController;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\EventController.
 */
class EventControllerTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Controller\EventController
     */
    protected $subject;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TypoScriptFrontendController
     */
    protected $tsfe;

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
                'createEventDemandObjectFromSettings',
                'createCategoryDemandObjectFromSettings',
                'createForeignRecordDemandObjectFromSettings',
                'overwriteEventDemandObject',
                'getSysLanguageUid',
                'persistAll'
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
     * @test
     */
    public function createEventDemandObjectFromSettingsWithoutCategory()
    {
        $mockController = $this->getMockBuilder(EventController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->getMock();

        $settings = [
            'displayMode' => 'all',
            'storagePage' => 1,
            'categoryConjunction' => 'AND',
            'category' => 10,
            'includeSubcategories' => true,
            'topEventRestriction' => 2,
            'orderField' => 'title',
            'orderFieldAllowed' => 'title',
            'orderDirection' => 'asc',
            'queryLimit' => 10,
            'location' => 1,
            'organisator' => 1
        ];

        $mockDemand = $this->getMockBuilder(EventDemand::class)->getMock();
        $mockDemand->expects(self::at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects(self::at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects(self::at(2))->method('setCategoryConjunction')->with('AND');
        $mockDemand->expects(self::at(3))->method('setCategory')->with(10);
        $mockDemand->expects(self::at(4))->method('setIncludeSubcategories')->with(true);
        $mockDemand->expects(self::at(5))->method('setTopEventRestriction')->with(2);
        $mockDemand->expects(self::at(6))->method('setOrderField')->with('title');
        $mockDemand->expects(self::at(7))->method('setOrderFieldAllowed')->with('title');
        $mockDemand->expects(self::at(8))->method('setOrderDirection')->with('asc');
        $mockDemand->expects(self::at(9))->method('setQueryLimit')->with(10);
        $mockDemand->expects(self::at(10))->method('setLocation')->with(1);
        $mockDemand->expects(self::at(11))->method('setOrganisator')->with(1);

        $objectManager = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($mockDemand);
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createEventDemandObjectFromSettings($settings);
    }

    /**
     * @test
     */
    public function createCategoryDemandObjectFromSettingsTest()
    {
        $mockController = $this->getMockBuilder(EventController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->getMock();

        $settings = [
            'storagePage' => 1,
            'category' => 10,
            'restrictForeignRecordsToStoragePage' => false,
            'categoryMenu' => [
                'categories' => '1,2,3',
                'includeSubcategories' => false
            ]
        ];

        $mockDemand = $this->getMockBuilder(CategoryDemand::class)->getMock();
        $mockDemand->expects(self::at(0))->method('setStoragePage')->with(1);
        $mockDemand->expects(self::at(1))->method('setRestrictToStoragePage')->with(false);
        $mockDemand->expects(self::at(2))->method('setCategories')->with('1,2,3');
        $mockDemand->expects(self::at(3))->method('setIncludeSubcategories')->with(false);

        $objectManager = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($mockDemand);
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createCategoryDemandObjectFromSettings($settings);
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
        self::assertSame(1, $resultDemand->getCategory());
    }

    /**
     * @test
     */
    public function initializeSaveRegistrationActionSetsDateFormat()
    {
        $settings = [
            'registration' => [
                'formatDateOfBirth' => 'd.m.Y'
            ]
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
        $mockArguments->expects(self::at(0))->method('getArgument')->with('registration')->willReturn(
            $mockRegistrationArgument
        );

        $mockRequest = $this->getMockBuilder(Request::class)->getMock();
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
        $demand = new EventDemand();
        $foreignRecordDemand = new ForeignRecordDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $this->subject->expects(self::once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->willReturn($foreignRecordDemand);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => [],
            'eventDemand' => $demand
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function listActionOverridesDemandAndFetchesAllEventsFromRepositoryAndAssignsThemToView()
    {
        $eventDemand = new EventDemand();
        $categoryDemand = new CategoryDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $overrideDemand = ['category' => 10];

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createCategoryDemandObjectFromSettings')
            ->with($settings)->willReturn($categoryDemand);
        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($eventDemand);
        $this->subject->expects(self::once())->method('overwriteEventDemandObject')->willReturn($eventDemand);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     */
    public function listActionDoesNotOverrideDemandIfDisabled()
    {
        $eventDemand = new EventDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $overrideDemand = ['category' => 10];

        $settings = ['disableOverrideDemand' => 1];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($eventDemand);

        // Ensure overwriteDemand is not called
        $this->subject->expects(self::never())->method('overwriteEventDemandObject');

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['addPageCacheTagsByEventDemandObject'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addPageCacheTagsByEventDemandObject');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyListViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     */
    public function detailActionAssignsEventToView()
    {
        $event = new Event();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(['event' => $event]);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['addCacheTagsByEventRecords'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addCacheTagsByEventRecords');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

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
        $this->inject($this->subject, 'paymentService', $mockPaymentService);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'event' => $event,
            'paymentMethods' => ['invoice']
        ]);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->registrationAction($event);
    }

    /**
     * @test
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

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
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $event = $this->getMockBuilder(Event::class)->getMock();
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
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
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
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
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::once())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
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
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(11);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
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
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(6);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
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
        $this->inject($this->subject, 'hashService', $hashService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)->setMethods(['emailNotUnique'])->getMock();
        $mockRegistrationService->expects(self::once())->method('emailNotUnique')->willReturn(true);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $repoRegistrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $repoRegistrations->expects(self::any())->method('count')->willReturn(10);

        // Inject mock of registrationRepository to registrationService
        $registrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->setMethods(['findEventRegistrationsByEmail'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::any())->method('findEventRegistrationsByEmail')->willReturn($repoRegistrations);
        $this->inject($mockRegistrationService, 'registrationRepository', $registrationRepository);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getEmail')->willReturn('email@domain.tld');

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
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
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->inject($this->subject, 'hashService', $hashService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkRegistrationSuccess'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST]);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationSavedEvent($registration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyCreateDependingRegistrationsEvent($registration, false, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->inject($this->subject, 'hashService', $hashService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationSavedEvent($registration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyCreateDependingRegistrationsEvent($registration, false, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getUid')->willReturn($regUid);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn($regHmac);
        $this->inject($this->subject, 'hashService', $hashService);

        // Inject settings so autoconfirmation is disabled
        $settings = [
            'registration' => [
                'autoConfirmation' => 1
            ]
        ];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('redirect')->with(
            'confirmRegistration',
            null,
            null,
            ['reguid' => $regUid, 'hmac' => $regHmac]
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationSavedEvent($registration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyCreateDependingRegistrationsEvent($registration, false, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);
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
        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getUid')->willReturn($regUid);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getRegistration')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);
        $event->expects(self::any())->method('getEnableAutoconfirm')->willReturn(true);
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkRegistrationSuccess'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn($regHmac);
        $this->inject($this->subject, 'hashService', $hashService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'confirmRegistration',
            null,
            null,
            ['reguid' => $regUid, 'hmac' => $regHmac]
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationSavedEvent($registration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyCreateDependingRegistrationsEvent($registration, false, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action creates multiple registrations
     * if getAmountOfRegistrations > 1
     *
     * @test
     */
    public function saveRegistrationCreatesMultipleRegistrationIfAmountOfRegistrationsGreatherThanOne()
    {
        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('somehmac');
        $this->inject($this->subject, 'hashService', $hashService);

        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(2);

        $registrations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::any())->method('getRegistration')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);
        $event->expects(self::any())->method('getFreePlaces')->willReturn(10);
        $event->expects(self::any())->method('getMaxRegistrationsPerUser')->willReturn(2);
        $event->expects(self::any())->method('getUid')->willReturn(1);
        $event->expects(self::any())->method('getPid')->willReturn(1);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $notificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $notificationService->expects(self::once())->method('sendUserMessage');
        $notificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkRegistrationSuccess', 'createDependingRegistrations'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkRegistrationSuccess')
            ->willReturn([true, RegistrationResult::REGISTRATION_SUCCESSFUL]);
        $mockRegistrationService->expects(self::once())->method('createDependingRegistrations');
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $this->subject->expects(self::once())->method('redirect')->with(
            'saveRegistrationResult',
            null,
            null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']
        );

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationSavedEvent($registration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyCreateDependingRegistrationsEvent($registration, true, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $this->inject($this->subject, 'hashService', $hashService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::never())->method('findByUid')->with(1);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.registrationsuccessfulwrongeventhmac',
            'titleKey' => 'registrationResult.title.failed',
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

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
                'registrationResult.title.failed'
            ],
            'RegistrationDeadlineExpired' => [
                RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED,
                1,
                'somehmac',
                'event.message.registrationfaileddeadlineexpired',
                'registrationResult.title.failed'
            ],
            'EventFull' => [
                RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS,
                1,
                'somehmac',
                'event.message.registrationfailedmaxparticipants',
                'registrationResult.title.failed'
            ],
            'RegistrationSuccessful' => [
                RegistrationResult::REGISTRATION_SUCCESSFUL,
                1,
                'somehmac',
                'event.message.registrationsuccessful',
                'registrationResult.title.successful'
            ],
            'RegistrationNotEnabled' => [
                RegistrationResult::REGISTRATION_NOT_ENABLED,
                1,
                'somehmac',
                'event.message.registrationfailednotenabled',
                'registrationResult.title.failed'
            ],
            'NotEnoughFreePlaces' => [
                RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES,
                1,
                'somehmac',
                'event.message.registrationfailednotenoughfreeplaces',
                'registrationResult.title.failed'
            ],
            'MaxAmountRegistrationsExceeded' => [
                RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED,
                1,
                'somehmac',
                'event.message.registrationfailedmaxamountregistrationsexceeded',
                'registrationResult.title.failed'
            ],
            'EmailNotUnique' => [
                RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE,
                1,
                'somehmac',
                'event.message.registrationfailedemailnotunique',
                'registrationResult.title.failed'
            ],
            'UnknownResult' => [
                -1,
                1,
                'somehmac',
                '',
                ''
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
        $this->inject($this->subject, 'hashService', $hashService);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::any())->method('findByUid')->with($eventUid);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => $message,
            'titleKey' => $title,
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

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
            'failed' => true
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkConfirmRegistration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')
            ->willReturn($returnedArray);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyConfirmRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('setConfirmed')->with(true);
        $mockRegistration->expects(self::any())->method('getEvent');
        $mockRegistration->expects(self::once())->method('getAmountOfRegistrations')->willReturn(2);

        $variables = [
            'messageKey' => 'event.message.confirmation_successful',
            'titleKey' => 'confirmRegistration.title.successful',
            'event' => null,
            'registration' => $mockRegistration,
            'failed' => false
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_successful',
            'confirmRegistration.title.successful'
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'])
            ->disableOriginalConstructor()
            ->getMock();

        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')
            ->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('confirmDependingRegistrations')
            ->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('update');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationConfirmedEvent($mockRegistration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyConfirmRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('setConfirmed')->with(true);
        $mockRegistration->expects(self::once())->method('getAmountOfRegistrations')->willReturn(2);
        $mockRegistration->expects(self::any())->method('getWaitlist')->willReturn(true);
        $mockRegistration->expects(self::any())->method('getEvent');

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.confirmation_waitlist_successful',
            'titleKey' => 'confirmRegistrationWaitlist.title.successful',
            'event' => null,
            'registration' => $mockRegistration,
            'failed' => false
        ]);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_waitlist_successful',
            'confirmRegistrationWaitlist.title.successful'
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkConfirmRegistration')->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('confirmDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->setMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('update');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationConfirmedEvent($mockRegistration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new ModifyConfirmRegistrationViewVariablesEvent(
                [
                    'failed' => false,
                    'messageKey' => 'event.message.confirmation_waitlist_successful',
                    'titleKey' => 'confirmRegistrationWaitlist.title.successful',
                    'event' => null,
                    'registration' => $mockRegistration,
                ],
                $this->subject
            )
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
            'failed' => true
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkCancelRegistration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkCancelRegistration')->willReturn($returnedArray);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifyCancelRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $mockEvent->expects(self::any())->method('getCancelDeadline')->willReturn(new \DateTime('yesterday'));

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(2);
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.cancel_successful',
            'cancelRegistration.title.successful'
        ];

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['checkCancelRegistration', 'cancelDependingRegistrations'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('checkCancelRegistration')->willReturn($returnedArray);
        $mockRegistrationService->expects(self::once())->method('cancelDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMockBuilder(NotificationService::class)->getMock();
        $mockNotificationService->expects(self::once())->method('sendUserMessage');
        $mockNotificationService->expects(self::once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMockBuilder(
            RegistrationRepository::class
        )->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('remove');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['flushEventCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('flushEventCache');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $variables = [
            'messageKey' => 'event.message.cancel_successful',
            'titleKey' => 'cancelRegistration.title.successful',
            'event' => $mockEvent,
            'failed' => false
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::at(0))->method('dispatch')->with(
            new AfterRegistrationCancelledEvent($mockRegistration, $this->subject)
        );
        $eventDispatcher->expects(self::at(1))->method('dispatch')->with(
            new WaitlistMoveUpEvent($mockEvent, $this->subject)
        );
        $eventDispatcher->expects(self::at(2))->method('dispatch')->with(
            new ModifyCancelRegistrationViewVariablesEvent($variables, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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
        $mockSearchDemandPmConfig->expects(self::once())->method('allowAllProperties');
        $mockSearchDemandPmConfig->expects(self::once())->method('setTypeConverterOption')->with(
            self::equalTo(PersistentObjectConverter::class),
            self::equalTo(PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED),
            self::equalTo(true)
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

        $mockSearchDemandArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockSearchDemandArgument->expects(self::once())->method('getPropertyMappingConfiguration')->willReturn(
            $mockSearchDemandPmConfig
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

        $mockArguments = $this->getMockBuilder(Arguments::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockArguments->expects(self::at(0))->method('getArgument')->with('searchDemand')->willReturn(
            $mockStartDateArgument
        );
        $mockArguments->expects(self::at(1))->method('getArgument')->with('searchDemand')->willReturn(
            $mockEndDateArgument
        );
        $mockArguments->expects(self::at(2))->method('hasArgument')->with('searchDemand')->willReturn(
            true
        );
        $mockArguments->expects(self::at(3))->method('getArgument')->with('searchDemand')->willReturn(
            $mockSearchDemandArgument
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
                'dateFormat' => 'Y-m-d'
            ]
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
        $demand = new EventDemand();
        $foreignRecordDemand = new ForeignRecordDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $this->subject->expects(self::once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->willReturn($foreignRecordDemand);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'searchDemand' => null,
            'overwriteDemand' => [],
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifySearchViewVariablesEvent(
                $variables,
                $this->subject
            )
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->searchAction();
    }

    /**
     * @test
     */
    public function searchActionFetchesAllEventsFromRepositoryAndAssignsThemToViewWithSearchDemand()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $foreignRecordDemand = new ForeignRecordDemand();
        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $this->subject->expects(self::once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->willReturn($foreignRecordDemand);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $variables = [
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'speakers' => $allSpeakers,
            'searchDemand' => $searchDemand,
            'overwriteDemand' => [],
        ];

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with($variables);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ModifySearchViewVariablesEvent(
                $variables,
                $this->subject
            )
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     */
    public function searchActionSetsSearchDemandFieldsIfSearchDemandGiven()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $demand = $this->getMockBuilder(EventDemand::class)
            ->setMethods(['setSearchDemand'])
            ->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

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

        $demand = $this->getMockBuilder(EventDemand::class)->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $settings = [
            'search' => [
                'adjustTime' => 1
            ]
        ];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(
            LocationRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     */
    public function searchActionOverwritesDemandFieldsIfOverwriteDemandObjectGiven()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $demand = $this->getMockBuilder(EventDemand::class)->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $overrideDemand = ['category' => 10];
        $this->subject->expects(self::once())->method('overwriteEventDemandObject')->willReturn($demand);

        $settings = ['disableOverrideDemand' => 0];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     */
    public function searchActionDoesNotOverridesDemandIfOverwriteDemandDisabled()
    {
        $searchDemand = $this->getMockBuilder(SearchDemand::class)->getMock();
        $searchDemand->expects(self::once())->method('setFields');

        $demand = $this->getMockBuilder(EventDemand::class)->getMock();
        $demand->expects(self::once())->method('setSearchDemand')->with($searchDemand);

        $overrideDemand = ['category' => 10];
        $this->subject->expects(self::never())->method('overwriteEventDemandObject');

        $settings = ['disableOverrideDemand' => 1];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects(self::once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->willReturn($demand);

        $allEvents = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allCategories = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allLocations = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allOrganisators = $this->getMockBuilder(ObjectStorage::class)->getMock();
        $allSpeakers = $this->getMockBuilder(ObjectStorage::class)->getMock();

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventRepository->expects(self::once())->method('findDemanded')->willReturn($allEvents);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMockBuilder(CategoryRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findDemanded')->willReturn($allCategories);
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMockBuilder(LocationRepository::class)
            ->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $locationRepository->expects(self::once())->method('findDemanded')->willReturn($allLocations);
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMockBuilder(
            OrganisatorRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $organisatorRepository->expects(self::once())->method('findDemanded')->willReturn($allOrganisators);
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $speakerRepository = $this->getMockBuilder(
            SpeakerRepository::class
        )->setMethods(['findDemanded'])
            ->disableOriginalConstructor()
            ->getMock();
        $speakerRepository->expects(self::once())->method('findDemanded')->willReturn($allSpeakers);
        $this->inject($this->subject, 'speakerRepository', $speakerRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     */
    public function detailActionShowsEventIfEventGiven()
    {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assignMultiple')->with(['event' => $mockEvent]);
        $this->inject($this->subject, 'view', $view);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $eventCacheService = $this->getMockBuilder(EventCacheService::class)
            ->setMethods(['addCacheTagsByEventRecords'])
            ->disableOriginalConstructor()
            ->getMock();
        $eventCacheService->expects(self::once())->method('addCacheTagsByEventRecords');
        $this->inject($this->subject, 'eventCacheService', $eventCacheService);

        $this->subject->detailAction($mockEvent);
    }

    /**
     * @test
     */
    public function handleEventNotFoundIsSkippedWhenNoSetting()
    {
        $settings = [
            'event' => [
                'errorHandling' => ''
            ]
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        self::assertNull($mock->_call('handleEventNotFoundError', $settings));
    }

    /**
     * @test
     */
    public function handleEventNotFoundShows404Page()
    {
        $settings = [
            'event' => [
                'errorHandling' => 'pageNotFoundHandler'
            ]
        ];

        $GLOBALS['TYPO3_REQUEST'] = new ServerRequest();

        $mockErrorController = $this->getMockBuilder(ErrorController::class)->getMock();
        GeneralUtility::addInstance(ErrorController::class, $mockErrorController);
        $this->expectException(ImmediateResponseException::class);
        $mock = $this->getAccessibleMock(EventController::class, ['dummy']);
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
                'errorHandling' => 'redirectToListView'
            ]
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
                'errorHandling' => 'redirectToListView'
            ]
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->expects(self::once())->method('redirect')->with('list', null, null, null, 1);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     */
    public function handleEventNotFoundRendersStandaloneView()
    {
        $settings = [
            'event' => [
                'errorHandling' => 'showStandaloneTemplate,EXT:sf_event_mgt/Resources/Private/Templates/Event/EventNotFound.html'
            ]
        ];

        $mockEventController = $this->getAccessibleMock(EventController::class, ['redirect']);

        $standAloneView = $this->prophesize(StandaloneView::class);
        $standAloneView->setTemplatePathAndFilename(\Prophecy\Argument::any())->shouldBeCalled();
        $standAloneView->render()->willReturn('foo');
        GeneralUtility::addInstance(StandaloneView::class, $standAloneView->reveal());

        $mockEventController->_call('handleEventNotFoundError', $settings);
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
            'lastDayOfCalendar' => strtotime('05.02.2017')
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
            ->setMethods(['getCalendarDateRange'])
            ->disableOriginalConstructor()
            ->getMock();
        $calendarService->expects(self::once())->method('getCalendarDateRange')->willReturn($calendarDateRangeResult);
        $this->inject($mockController, 'calendarService', $calendarService);

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
            ->setMethods(['findByUid'])
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
            ->setMethods(['getContentObject'])->disableOriginalConstructor()->getMock();
        $mockConfigurationManager->expects(self::once())->method('getContentObject')
            ->willReturn($mockContentObjectRenderer);
        $mockedController->_set('configurationManager', $mockConfigurationManager);

        $mockEventRepository = $this->getMockBuilder(EventRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockEventRepository->expects(self::once())->method('findByUid')->with(123)
            ->willReturn($mockEvent);
        $mockedController->_set('eventRepository', $mockEventRepository);

        $mockedController->_set('settings', ['detail' => ['isShortcut' => 1]]);
        self::assertEquals($mockEvent, $mockedController->_call('evaluateIsShortcutSetting', null));
    }
}
