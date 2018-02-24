<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\EventController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventControllerTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Controller\EventController
     */
    protected $subject = null;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|TypoScriptFrontendController
     */
    protected $tsfe = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\Controller\\EventController',
            [
                'redirect',
                'forward',
                'addFlashMessage',
                'createEventDemandObjectFromSettings',
                'createCategoryDemandObjectFromSettings',
                'createForeignRecordDemandObjectFromSettings',
                'overwriteEventDemandObject'
            ],
            [],
            '',
            false
        );
        $this->tsfe = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            ['pageNotFoundAndExit'],
            [],
            '',
            false
        );
        $GLOBALS['TSFE'] = $this->tsfe;
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
    public function createEventDemandObjectFromSettingsWithoutCategory()
    {
        $mockController = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Controller\\EventController',
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );

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

        $mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            [], [], '', false);
        $mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(2))->method('setCategoryConjunction')->with('AND');
        $mockDemand->expects($this->at(3))->method('setCategory')->with(10);
        $mockDemand->expects($this->at(4))->method('setIncludeSubcategories')->with(true);
        $mockDemand->expects($this->at(5))->method('setTopEventRestriction')->with(2);
        $mockDemand->expects($this->at(6))->method('setOrderField')->with('title');
        $mockDemand->expects($this->at(7))->method('setOrderFieldAllowed')->with('title');
        $mockDemand->expects($this->at(8))->method('setOrderDirection')->with('asc');
        $mockDemand->expects($this->at(9))->method('setQueryLimit')->with(10);
        $mockDemand->expects($this->at(10))->method('setLocation')->with(1);
        $mockDemand->expects($this->at(11))->method('setOrganisator')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            [], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createEventDemandObjectFromSettings($settings);
    }

    /**
     * @test
     * @return void
     */
    public function createCategoryDemandObjectFromSettingsTest()
    {
        $mockController = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Controller\\EventController',
            ['redirect', 'forward', 'addFlashMessage'],
            [],
            '',
            false
        );

        $settings = [
            'storagePage' => 1,
            'category' => 10,
            'restrictForeignRecordsToStoragePage' => false,
            'categoryMenu' => [
                'categories' => '1,2,3',
                'includeSubcategories' => false
            ]
        ];

        $mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\CategoryDemand', [], [], '', false);
        $mockDemand->expects($this->at(0))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(1))->method('setRestrictToStoragePage')->with(false);
        $mockDemand->expects($this->at(2))->method('setCategories')->with('1,2,3');
        $mockDemand->expects($this->at(3))->method('setIncludeSubcategories')->with(false);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager', [], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createCategoryDemandObjectFromSettings($settings);
    }

    /**
     * Test if overwriteDemand ignores properties in $ignoredSettingsForOverwriteDemand
     *
     * @test
     * @return void
     */
    public function overwriteDemandObjectIgnoresIgnoredProperties()
    {
        $demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $overwriteDemand = ['storagePage' => 1, 'category' => 1];

        $mockController = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            ['redirect', 'forward', 'addFlashMessage'], [], '', false);
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        $this->assertNull($resultDemand->getStoragePage());
    }

    /**
     * Test if overwriteDemand sets a property in the given demand
     *
     * @test
     * @return void
     */
    public function overwriteDemandObjectSetsCategoryProperty()
    {
        $demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $overwriteDemand = ['category' => 1];

        $mockController = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            ['redirect', 'forward', 'addFlashMessage'], [], '', false);
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        $this->assertSame(1, $resultDemand->getCategory());
    }

    /**
     * @test
     * @return void
     */
    public function initializeSaveRegistrationActionSetsDateFormat()
    {
        $settings = [
            'registration' => [
                'formatDateOfBirth' => 'd.m.Y'
            ]
        ];

        $mockPropertyMapperConfig = $this->getMock(MvcPropertyMappingConfiguration::class, [], [], '', false);
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
            $this->equalTo('dateFormat'),
            $this->equalTo('d.m.Y')
        );

        $mockDateOfBirthPmConfig = $this->getMock(MvcPropertyMappingConfiguration::class, [], [], '', false);
        $mockDateOfBirthPmConfig->expects($this->once())->method('forProperty')->with('dateOfBirth')->will(
            $this->returnValue($mockPropertyMapperConfig));

        $mockRegistrationArgument = $this->getMock(Argument::class, [], [], '', false);
        $mockRegistrationArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockDateOfBirthPmConfig));

        $mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
            [], [], '', false);
        $mockArguments->expects($this->at(0))->method('getArgument')->with('registration')->will(
            $this->returnValue($mockRegistrationArgument));

        $mockRequest = $this->getMock(Request::class, [], [], '', false);
        $mockRequest->expects($this->once())->method('getArguments')->will($this->returnValue([]));

        $this->subject->_set('request', $mockRequest);
        $this->subject->_set('arguments', $mockArguments);
        $this->subject->_set('settings', $settings);
        $this->subject->initializeSaveRegistrationAction();
    }


    /**
     * @test
     * @return void
     */
    public function listActionFetchesAllEventsFromRepositoryAndAssignsThemToView()
    {
        $demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $foreignRecordDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allOrganisators = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $category = 0;

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $this->subject->expects($this->once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($foreignRecordDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'overwriteDemand' => [],
            'eventDemand' => $demand
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     * @return void
     */
    public function listActionOverridesDemandAndFetchesAllEventsFromRepositoryAndAssignsThemToView()
    {
        $eventDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $categoryDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allOrganisators = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $overrideDemand = ['category' => 10];

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createCategoryDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($categoryDemand));
        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($eventDemand));
        $this->subject->expects($this->once())->method('overwriteEventDemandObject')->will($this->returnValue($eventDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     * @return void
     */
    public function listActionDoesNotOverrideDemandIfDisabled()
    {
        $eventDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allOrganisators = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $overrideDemand = ['category' => 10];

        $settings = ['disableOverrideDemand' => 1];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($eventDemand));

        // Ensure overwriteDemand is not called
        $this->subject->expects($this->never())->method('overwriteEventDemandObject');

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'overwriteDemand' => $overrideDemand,
            'eventDemand' => $eventDemand
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction($overrideDemand);
    }

    /**
     * @test
     * @return void
     */
    public function detailActionAssignsEventToView()
    {
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('event', $event);
        $this->inject($this->subject, 'view', $view);

        $this->subject->detailAction($event);
    }

    /**
     * Test if ICalendarService is called when downloading a iCal file
     *
     * @test
     *
     * @return void
     */
    public function icalDownloadActionCallsICalendarServiceDownloadiCalendarFile()
    {
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', []);
        $icalendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ICalendarService',
            [], [], '', false);
        $icalendarService->expects($this->once())->method('downloadiCalendarFile')->with($this->equalTo($event));
        $this->inject($this->subject, 'icalendarService', $icalendarService);
        $this->subject->icalDownloadAction($event);
    }

    /**
     * @test
     * @return void
     */
    public function registrationActionAssignsEventToView()
    {
        $event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();

        $mockPaymentService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\PaymentService', ['getPaymentMethods'],
            [], '', false);
        $mockPaymentService->expects($this->once())->method('getPaymentMethods')->will($this->returnValue(['invoice']));
        $this->inject($this->subject, 'paymentService', $mockPaymentService);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'event' => $event,
            'paymentMethods' => ['invoice']
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->registrationAction($event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(false));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_NOT_ENABLED, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDeadlineExpired()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->any())->method('getRegistrationDeadline')->will($this->returnValue($deadline));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfEventExpired()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfMaxParticipantsReached()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->once())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->once())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsGreaterThanRemainingPlaces()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(11));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(20));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(6));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(20));
        $event->expects($this->once())->method('getMaxRegistrationsPerUser')->will($this->returnValue(5));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfUniqueEmailCheckEnabledAndEmailAlreadyRegistered()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $repoRegistrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $repoRegistrations->expects($this->any())->method('count')->will($this->returnValue(10));

        // Inject mock of registrationRepository to registrationService
        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findEventRegistrationsByEmail'], [], '', false);
        $registrationRepository->expects($this->any())->method('findEventRegistrationsByEmail')->will($this->returnValue($repoRegistrations));
        $this->inject($registrationService, 'registrationRepository', $registrationRepository);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getEmail')->will($this->returnValue('email@domain.tld'));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getUniqueEmailCheck')->will($this->returnValue(true));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with no autoConfirmation saves the
     * registration if maxParticipants is reached and waitlist is enabled
     *
     * @test
     * @return void
     */
    public function saveRegistrationActionWithoutAutoConfirmationAndWaitlistRedirectsWithMessageIfRegistrationSuccessful()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);
        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getEnableWaitlist')->will($this->returnValue(true));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock(EventRepository::class,
            ['update'], [], '', false);
        $eventRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $notificationService->expects($this->once())->method('sendUserMessage');
        $notificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with no autoConfirmation saves the
     * registration and redirects to the saveRegistrationResult action.
     *
     * @test
     * @return void
     */
    public function saveRegistrationActionWithoutAutoConfirmationRedirectsToWithMessageIfRegistrationSuccessful()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock(EventRepository::class,
            ['update'], [], '', false);
        $eventRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $notificationService->expects($this->once())->method('sendUserMessage');
        $notificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with autoConfirmation (settings) saves the
     * registration and redirects to the confirmationRegistration action.
     *
     * @test
     * @return void
     */
    public function saveRegistrationWithSettingAutoConfirmationActionRedirectsToConfirmationWithMessage(
    )
    {
        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getUid')->will($this->returnValue($regUid));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock(EventRepository::class,
            ['update'], [], '', false);
        $eventRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['getCurrentFeUserObject'], [], '', false);
        $registrationService->expects($this->once())->method('getCurrentFeUserObject');
        $this->inject($this->subject, 'registrationService', $registrationService);

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue($regHmac));
        $this->inject($this->subject, 'hashService', $hashService);

        // Inject settings so autoconfirmation is disabled
        $settings = [
            'registration' => [
                'autoConfirmation' => 1
            ]
        ];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('redirect')->with('confirmRegistration', null, null,
            ['reguid' => $regUid, 'hmac' => $regHmac]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with autoConfirmation (in event) saves the
     * registration and redirects to the confirmationRegistration action.
     *
     * @test
     * @return void
     */
    public function saveRegistrationWithEventAutoConfirmationActionRedirectsToConfirmationWithMessage(
    )
    {
        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getUid')->will($this->returnValue($regUid));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getEnableAutoconfirm')->will($this->returnValue(true));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock(EventRepository::class, ['update'], [], '', false);
        $eventRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['getCurrentFeUserObject'], [], '', false);
        $registrationService->expects($this->once())->method('getCurrentFeUserObject');
        $this->inject($this->subject, 'registrationService', $registrationService);

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue($regHmac));
        $this->inject($this->subject, 'hashService', $hashService);

        $this->subject->expects($this->once())->method('redirect')->with('confirmRegistration', null, null,
            ['reguid' => $regUid, 'hmac' => $regHmac]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action creates multiple registrations
     * if getAmountOfRegistrations > 1
     *
     * @test
     * @return void
     */
    public function saveRegistrationCreatesMultipleRegistrationIfAmountOfRegistrationsGreatherThanOne()
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['generateHmac'], [], '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('somehmac'));
        $this->inject($this->subject, 'hashService', $hashService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(2));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxRegistrationsPerUser')->will($this->returnValue(2));
        $event->expects($this->any())->method('getUid')->will($this->returnValue(1));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock(EventRepository::class, ['update'], [], '', false);
        $eventRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $notificationService->expects($this->once())->method('sendUserMessage');
        $notificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            ['persistAll'], [], '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['createDependingRegistrations'], [], '', false);
        $registrationService->expects($this->once())->method('createDependingRegistrations')->with($registration);
        $this->inject($this->subject, 'registrationService', $registrationService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL, 'eventuid' => 1, 'hmac' => 'somehmac']);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfWrongHmacGiven()
    {
        $eventUid = 1;
        $hmac = 'wrongmac';

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(false));
        $this->inject($this->subject, 'hashService', $hashService);

        $eventRepository = $this->getMock(EventRepository::class, ['findByUid'], [], '', false);
        $eventRepository->expects($this->never())->method('findByUid')->with(1);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
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
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessage($result, $eventUid, $hmac, $message, $title)
    {
        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $hashService->expects($this->once())->method('validateHmac')->with('event-' . $eventUid, $hmac)->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $hashService);

        $eventRepository = $this->getMock(EventRepository::class,
            ['findByUid'], [], '', false);
        $eventRepository->expects($this->any())->method('findByUid')->with($eventUid);
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
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
     * @return void
     */
    public function confirmRegistrationActionShowsExpectedMessageIfCheckConfirmRegistrationFailed()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.confirmation_failed_wrong_hmac',
            'titleKey' => 'confirmRegistration.title.failed',
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        ];

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['checkConfirmRegistration'], [], '', false);
        $mockRegistrationService->expects($this->once())->method('checkConfirmRegistration')->will($this->returnValue($returnedArray));
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $this->subject->confirmRegistrationAction(1, 'INVALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkConfirmRegistration succeeds.
     * Also checks, if messages are sent and if registration gets confirmed.
     *
     * @test
     * @return void
     */
    public function confirmRegistrationActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.confirmation_successful',
            'titleKey' => 'confirmRegistration.title.successful',
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->once())->method('setConfirmed')->with(true);
        $mockRegistration->expects($this->any())->method('getEvent');
        $mockRegistration->expects($this->once())->method('getAmountOfRegistrations')->will($this->returnValue(2));

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_successful',
            'confirmRegistration.title.successful'
        ];

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'], [], '', false);
        $mockRegistrationService->expects($this->once())->method('checkConfirmRegistration')->will($this->returnValue($returnedArray));
        $mockRegistrationService->expects($this->once())->method('confirmDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $mockNotificationService->expects($this->once())->method('sendUserMessage');
        $mockNotificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['update'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkConfirmRegistration succeeds for waitist registrations
     * Also checks, if messages are sent and if registration gets confirmed.
     *
     * @test
     * @return void
     */
    public function confirmRegistrationWaitlistActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.confirmation_waitlist_successful',
            'titleKey' => 'confirmRegistrationWaitlist.title.successful',
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->once())->method('setConfirmed')->with(true);
        $mockRegistration->expects($this->once())->method('getAmountOfRegistrations')->will($this->returnValue(2));
        $mockRegistration->expects($this->any())->method('getWaitlist')->will($this->returnValue(true));
        $mockRegistration->expects($this->any())->method('getEvent');

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.confirmation_waitlist_successful',
            'confirmRegistrationWaitlist.title.successful'
        ];

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['checkConfirmRegistration', 'confirmDependingRegistrations', 'redirectPaymentEnabled'], [], '', false);
        $mockRegistrationService->expects($this->once())->method('checkConfirmRegistration')->will($this->returnValue($returnedArray));
        $mockRegistrationService->expects($this->once())->method('confirmDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $mockNotificationService->expects($this->once())->method('sendUserMessage');
        $mockNotificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['update'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $this->subject->confirmRegistrationAction(1, 'VALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkCancelRegistration fails
     *
     * @test
     * @return void
     */
    public function cancelRegistrationActionShowsMessageIfCheckCancelRegistrationFailed()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.cancel_failed_wrong_hmac',
            'titleKey' => 'cancelRegistration.title.failed',
            'event' => null
        ]);
        $this->inject($this->subject, 'view', $view);

        $returnedArray = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        ];

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['checkCancelRegistration'], [], '', false);
        $mockRegistrationService->expects($this->once())->method('checkCancelRegistration')->will($this->returnValue($returnedArray));
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $this->subject->cancelRegistrationAction(1, 'INVALID-HMAC');
    }

    /**
     * Test if expected message is shown if checkCancelRegistration succeeds.
     * Also checks, if messages are sent and if registration gets removed.
     *
     * @test
     * @return void
     */
    public function cancelRegistrationActionShowsMessageIfCheckCancelRegistrationSucceeds()
    {
        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(2));
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $returnedArray = [
            false,
            $mockRegistration,
            'event.message.cancel_successful',
            'cancelRegistration.title.successful'
        ];

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['checkCancelRegistration', 'cancelDependingRegistrations'], [], '', false);
        $mockRegistrationService->expects($this->once())->method('checkCancelRegistration')->will($this->returnValue($returnedArray));
        $mockRegistrationService->expects($this->once())->method('cancelDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            [], [], '', false);
        $mockNotificationService->expects($this->once())->method('sendUserMessage');
        $mockNotificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['remove'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('remove');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockUtilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            ['clearCacheForConfiguredUids'], [], '', false);
        $mockUtilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $mockUtilityService);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'messageKey' => 'event.message.cancel_successful',
            'titleKey' => 'cancelRegistration.title.successful',
            'event' => $mockEvent
        ]);
        $this->inject($this->subject, 'view', $view);

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
     * @return void
     */
    public function searchActionFetchesAllEventsFromRepositoryAndAssignsThemToViewForNoSearchDemand()
    {
        $demand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $foreignRecordDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allOrganisators = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $this->subject->expects($this->once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($foreignRecordDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'searchDemand' => null,
            'overwriteDemand' => [],
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction();
    }

    /**
     * @test
     * @return void
     */
    public function searchActionFetchesAllEventsFromRepositoryAndAssignsThemToViewWithSearchDemand()
    {
        $searchDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand', [], [], '', false);
        $searchDemand->expects($this->once())->method('setFields');

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand', ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $foreignRecordDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand();
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $allOrganisators = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $this->subject->expects($this->once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($foreignRecordDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assignMultiple')->with([
            'events' => $allEvents,
            'categories' => $allCategories,
            'locations' => $allLocations,
            'organisators' => $allOrganisators,
            'searchDemand' => $searchDemand,
            'overwriteDemand' => [],
        ]);
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     * @return void
     */
    public function searchActionSetsSearchDemandFieldsIfSearchDemandGiven()
    {
        $searchDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand', [], [], '', false);
        $searchDemand->expects($this->once())->method('setFields');

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand', ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $settings = ['settings'];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     * @return void
     */
    public function searchActionSetsAdjustsDateFieldsIfAdjustDateSettingSetAndDateFieldsGiven()
    {
        $mockStartDate = $this->getMock('\DateTime', [], [], '', false);
        $mockStartDate->expects($this->once())->method('setTime')->with(0, 0, 0);

        $mockEndDate = $this->getMock('\DateTime', [], [], '', false);
        $mockEndDate->expects($this->once())->method('setTime')->with(23, 59, 59);

        $searchDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand', [], [], '', false);
        $searchDemand->expects($this->once())->method('setFields');
        $searchDemand->expects($this->any())->method('getStartDate')->will($this->returnValue($mockStartDate));
        $searchDemand->expects($this->any())->method('getEndDate')->will($this->returnValue($mockEndDate));

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand', ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $settings = [
            'search' => [
                'adjustTime' => 1
            ]
        ];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand);
    }

    /**
     * @test
     * @return void
     */
    public function searchActionOverwritesDemandFieldsIfOverwriteDemandObjectGiven()
    {
        $searchDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand', [], [], '', false);
        $searchDemand->expects($this->once())->method('setFields');

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand', ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $overrideDemand = ['category' => 10];
        $this->subject->expects($this->once())->method('overwriteEventDemandObject')->will($this->returnValue($demand));

        $settings = ['disableOverrideDemand' => 0];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     * @return void
     */
    public function searchActionDoesNotOverridesDemandIfOverwriteDemandDisabled()
    {
        $searchDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\SearchDemand', [], [], '', false);
        $searchDemand->expects($this->once())->method('setFields');

        $demand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand', ['setSearchDemand'], [], '', false);
        $demand->expects($this->once())->method('setSearchDemand')->with($searchDemand);

        $overrideDemand = ['category' => 10];
        $this->subject->expects($this->never())->method('overwriteEventDemandObject');

        $settings = ['disableOverrideDemand' => 1];
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            ['findDemanded'], [], '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            ['findDemanded'], [], '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            ['findDemanded'], [], '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $organisatorRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\OrganisatorRepository',
            ['findDemanded'], [], '', false);
        $organisatorRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allOrganisators));
        $this->inject($this->subject, 'organisatorRepository', $organisatorRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }

    /**
     * @test
     * @return void
     */
    public function detailActionShowsEventIfEventGiven()
    {
        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('event', $mockEvent);
        $this->inject($this->subject, 'view', $view);
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
        $this->assertNull($mock->_call('handleEventNotFoundError', $settings));
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

        $mock = $this->getAccessibleMock(EventController::class, ['dummy']);
        $this->tsfe->expects($this->once())->method('pageNotFoundAndExit');
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
        $mock->expects($this->once())->method('redirect')->with('list', null, null, null, 100);
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
        $mock->expects($this->once())->method('redirect')->with('list', null, null, null, 1);
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

        $mockStandaloneView = $this->getMock(StandaloneView::class, [], [], '', false);
        $mockStandaloneView->expects($this->once())->method('setTemplatePathAndFilename');
        $mockStandaloneView->expects($this->once())->method('render');

        $mockObjectManager = $this->getMock(ObjectManager::class, [], [], '', false);
        $mockObjectManager->expects($this->any())->method('get')->will($this->returnValue($mockStandaloneView));
        $this->inject($mockEventController, 'objectManager', $mockObjectManager);

        $mockEventController->_call('handleEventNotFoundError', $settings);
    }

    /**
     * @test
     * @return void
     */
    public function changeEventDemandToFullMonthDateRangeAppliesExpectedDatesAndUnsetsMonthAndYear()
    {
        $calendarDateRangeResult = [
            'firstDayOfMonth' => strtotime('01.01.2017'),
            'lastDayOfMonth' => strtotime('31.01.2017'),
            'firstDayOfCalendar' => strtotime('26.12.2016'),
            'lastDayOfCalendar' => strtotime('05.02.2017')
        ];

        $eventDemand = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
        $eventDemand->setYear(2017);
        $eventDemand->setMonth(1);

        $mockController = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            ['redirect', 'forward', 'addFlashMessage'], [], '', false);

        $calendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\CalendarService',
            ['getCalendarDateRange'], [], '', false);
        $calendarService->expects($this->once())->method('getCalendarDateRange')->will($this->returnValue($calendarDateRangeResult));
        $this->inject($mockController, 'calendarService', $calendarService);

        $resultDemand = $mockController->_call('changeEventDemandToFullMonthDateRange', $eventDemand);
        $this->assertEquals(0, $resultDemand->getMonth());
        $this->assertEquals(0, $resultDemand->getYear());
        $this->assertSame('26.12.2016 00:00:00', $resultDemand->getSearchDemand()->getStartDate()->format('d.m.Y H:i:s'));
        $this->assertSame('05.02.2017 23:59:59', $resultDemand->getSearchDemand()->getEndDate()->format('d.m.Y H:i:s'));
    }

}
