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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;

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
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            [
                'redirect',
                'forward',
                'addFlashMessage',
                'createEventDemandObjectFromSettings',
                'createCategoryDemandObjectFromSettings',
                'createForeignRecordDemandObjectFromSettings',
                'overwriteEventDemandObject'
            ], [], '', false);
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
            'category' => 10,
            'includeSubcategories' => true,
            'topEventRestriction' => 2,
            'orderField' => 'title',
            'orderFieldAllowed' => 'title',
            'orderDirection' => 'asc',
            'queryLimit' => 10,
            'location' => 1
        ];

        $mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            [], [], '', false);
        $mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(2))->method('setCategory')->with(10);
        $mockDemand->expects($this->at(3))->method('setIncludeSubcategories')->with(true);
        $mockDemand->expects($this->at(4))->method('setTopEventRestriction')->with(2);
        $mockDemand->expects($this->at(5))->method('setOrderField')->with('title');
        $mockDemand->expects($this->at(6))->method('setOrderFieldAllowed')->with('title');
        $mockDemand->expects($this->at(7))->method('setOrderDirection')->with('asc');
        $mockDemand->expects($this->at(8))->method('setQueryLimit')->with(10);
        $mockDemand->expects($this->at(9))->method('setLocation')->with(1);

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

        $mockPropertyMapperConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            [], [], '', false);
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
            $this->equalTo('dateFormat'),
            $this->equalTo('d.m.Y')
        );

        $mockDateOfBirthPmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            [], [], '', false);
        $mockDateOfBirthPmConfig->expects($this->once())->method('forProperty')->with('dateOfBirth')->will(
            $this->returnValue($mockPropertyMapperConfig));

        $mockRegistrationArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            [], [], '', false);
        $mockRegistrationArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockDateOfBirthPmConfig));

        $mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
            [], [], '', false);
        $mockArguments->expects($this->at(0))->method('getArgument')->with('registration')->will(
            $this->returnValue($mockRegistrationArgument));

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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('overwriteDemand', []);
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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('overwriteDemand', $overrideDemand);
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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('overwriteDemand', $overrideDemand);
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
        $view->expects($this->at(0))->method('assign')->with('event', $event);
        $view->expects($this->at(1))->method('assign')->with('paymentMethods', ['invoice']);
        $this->inject($this->subject, 'view', $view);

        $this->subject->registrationAction($event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled()
    {
        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(false));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_NOT_ENABLED]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDeadlineExpired()
    {
        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->any())->method('getRegistrationDeadline')->will($this->returnValue($deadline));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfEventExpired()
    {
        $registrationService = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
        $this->inject($this->subject, 'registrationService', $registrationService);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfMaxParticipantsReached()
    {
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

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsGreaterThanRemainingPlaces()
    {
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

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
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

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfUniqueEmailCheckEnabledAndEmailAlreadyRegistered()
    {
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

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE]);

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

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST]);

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

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL]);

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

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
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

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            ['createDependingRegistrations'], [], '', false);
        $registrationService->expects($this->once())->method('createDependingRegistrations')->with($registration);
        $this->inject($this->subject, 'registrationService', $registrationService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            ['result' => RegistrationResult::REGISTRATION_SUCCESSFUL]);

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfEventExpired()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailedeventexpired');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationDeadlineExpired()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfaileddeadlineexpired');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfEventFull()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailedmaxparticipants');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationSuccessful()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationsuccessful');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.successful');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_SUCCESSFUL);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfRegistrationNotEnabled()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailednotenabled');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_NOT_ENABLED);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfNotEnoughFreePlaces()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailednotenoughfreeplaces');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfMaxAmountRegistrationsExceeded()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailedmaxamountregistrationsexceeded');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsExpectedMessageIfEmailNotUnique()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.registrationfailedemailnotunique');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'registrationResult.title.failed');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationResultActionShowsNoMessageIfUnknownResultGiven()
    {
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            '');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            '');
        $this->inject($this->subject, 'view', $view);

        $this->subject->saveRegistrationResultAction(-1);
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
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.confirmation_failed_wrong_hmac');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'confirmRegistration.title.failed');
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
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.confirmation_successful');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'confirmRegistration.title.successful');
        $this->inject($this->subject, 'view', $view);

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->once())->method('setConfirmed')->with(true);
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
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.confirmation_waitlist_successful');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'confirmRegistrationWaitlist.title.successful');
        $this->inject($this->subject, 'view', $view);

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->once())->method('setConfirmed')->with(true);
        $mockRegistration->expects($this->once())->method('getAmountOfRegistrations')->will($this->returnValue(2));
        $mockRegistration->expects($this->any())->method('getWaitlist')->will($this->returnValue(true));

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
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.cancel_failed_wrong_hmac');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'cancelRegistration.title.failed');
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
        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('messageKey',
            'event.message.cancel_successful');
        $view->expects($this->at(1))->method('assign')->with('titleKey',
            'cancelRegistration.title.successful');
        $this->inject($this->subject, 'view', $view);

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(2));

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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('searchDemand', null);
        $view->expects($this->at(4))->method('assign')->with('overwriteDemand', []);
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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('searchDemand', $searchDemand);
        $view->expects($this->at(4))->method('assign')->with('overwriteDemand', []);
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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->searchAction($searchDemand, $overrideDemand);
    }
}
