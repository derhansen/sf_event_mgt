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

use DERHANSEN\SfEventMgt\Utility\RegistrationResult;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\EventController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
            array(
                'redirect',
                'forward',
                'addFlashMessage',
                'createEventDemandObjectFromSettings',
                'createForeignRecordDemandObjectFromSettings',
                'overwriteEventDemandObject'
            ), array(), '', false);
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
        $mockController = $this->getMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            array('redirect', 'forward', 'addFlashMessage'), array(), '', false);

        $settings = array(
            'displayMode' => 'all',
            'storagePage' => 1,
            'category' => 10,
            'topEventRestriction' => 2,
            'orderField' => 'title',
            'orderDirection' => 'asc',
            'queryLimit' => 10,
            'location' => 1
        );

        $mockDemand = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Dto\\EventDemand',
            array(), array(), '', false);
        $mockDemand->expects($this->at(0))->method('setDisplayMode')->with('all');
        $mockDemand->expects($this->at(1))->method('setStoragePage')->with(1);
        $mockDemand->expects($this->at(2))->method('setCategory')->with(10);
        $mockDemand->expects($this->at(3))->method('setTopEventRestriction')->with(2);
        $mockDemand->expects($this->at(4))->method('setOrderField')->with('title');
        $mockDemand->expects($this->at(5))->method('setOrderDirection')->with('asc');
        $mockDemand->expects($this->at(6))->method('setQueryLimit')->with(10);
        $mockDemand->expects($this->at(7))->method('setLocation')->with(1);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array(), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($mockDemand));
        $this->inject($mockController, 'objectManager', $objectManager);

        $mockController->createEventDemandObjectFromSettings($settings);
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
        $overwriteDemand = array('storagePage' => 1, 'category' => 1);

        $mockController = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
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
        $overwriteDemand = array('category' => 1);

        $mockController = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Controller\\EventController',
            array('redirect', 'forward', 'addFlashMessage'), array(), '', false);
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        $this->assertSame(1, $resultDemand->getCategory());
    }

    /**
     * @test
     * @return void
     */
    public function initializeSaveRegistrationActionSetsDateFormat()
    {
        $settings = array(
            'registration' => array(
                'formatDateOfBirth' => 'd.m.Y'
            )
        );

        $mockPropertyMapperConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            array(), array(), '', false);
        $mockPropertyMapperConfig->expects($this->any())->method('setTypeConverterOption')->with(
            $this->equalTo('TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter'),
            $this->equalTo('dateFormat'),
            $this->equalTo('d.m.Y')
        );

        $mockDateOfBirthPmConfig = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\MvcPropertyMappingConfiguration',
            array(), array(), '', false);
        $mockDateOfBirthPmConfig->expects($this->once())->method('forProperty')->with('dateOfBirth')->will(
            $this->returnValue($mockPropertyMapperConfig));

        $mockRegistrationArgument = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Argument',
            array(), array(), '', false);
        $mockRegistrationArgument->expects($this->once())->method('getPropertyMappingConfiguration')->will(
            $this->returnValue($mockDateOfBirthPmConfig));

        $mockArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments',
            array(), array(), '', false);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $category = 0;

        $settings = array('settings');
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($demand));

        $this->subject->expects($this->once())->method('createForeignRecordDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($foreignRecordDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            array('findDemanded'), array(), '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            array('findDemanded'), array(), '', false);
        $locationRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allLocations));
        $this->inject($this->subject, 'locationRepository', $locationRepository);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->at(0))->method('assign')->with('events', $allEvents);
        $view->expects($this->at(1))->method('assign')->with('categories', $allCategories);
        $view->expects($this->at(2))->method('assign')->with('locations', $allLocations);
        $view->expects($this->at(3))->method('assign')->with('overwriteDemand', null);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $overrideDemand = array('category' => 10);

        $settings = array('settings');
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($eventDemand));
        $this->subject->expects($this->once())->method('overwriteEventDemandObject')->will($this->returnValue($eventDemand));

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            array('findDemanded'), array(), '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            array('findDemanded'), array(), '', false);
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
        $allEvents = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allCategories = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $allLocations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $overrideDemand = array('category' => 10);

        $settings = array('disableOverrideDemand' => 1);
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('createEventDemandObjectFromSettings')
            ->with($settings)->will($this->returnValue($eventDemand));

        // Ensure overwriteDemand is not called
        $this->subject->expects($this->never())->method('overwriteDemandObject');

        $eventRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\EventRepository',
            array('findDemanded'), array(), '', false);
        $eventRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allEvents));
        $this->inject($this->subject, 'eventRepository', $eventRepository);

        $categoryRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\CategoryRepository',
            array('findDemanded'), array(), '', false);
        $categoryRepository->expects($this->once())->method('findDemanded')->will($this->returnValue($allCategories));
        $this->inject($this->subject, 'categoryRepository', $categoryRepository);

        $locationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository',
            array('findDemanded'), array(), '', false);
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
        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array());
        $icalendarService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\ICalendarService',
            array(), array(), '', false);
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

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $view->expects($this->once())->method('assign')->with('event', $event);
        $this->inject($this->subject, 'view', $view);

        $this->subject->registrationAction($event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDisabled()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(false));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_NOT_ENABLED));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfRegistrationDeadlineExpired()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->any())->method('getRegistrationDeadline')->will($this->returnValue($deadline));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationActionRedirectsWithMessageIfEventExpired()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfMaxParticipantsReached()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->once())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->once())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsGreaterThanRemainingPlaces()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(11));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(20));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * @test
     * @return void
     */
    public function saveRegistrationRedirectsWithMessageIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(6));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(20));
        $event->expects($this->once())->method('getMaxRegistrationsPerUser')->will($this->returnValue(5));

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED));

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
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('add'), array(), '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            array(), array(), '', false);
        $notificationService->expects($this->once())->method('sendUserMessage');
        $notificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            array('persistAll'), array(), '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            array('clearCacheForConfiguredUids'), array(), '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_SUCCESSFUL));

        $this->subject->saveRegistrationAction($registration, $event);
    }

    /**
     * Checks, if a saveRegistration action with autoConfirmation saves the
     * registration and redirects to the confirmationRegistration action.
     *
     * @test
     * @return void
     */
    public function saveRegistrationWithAutoConfirmationActionRedirectsToConfirmationWithMessageIfRegistrationSuccessful(
    )
    {
        $regUid = 1;
        $regHmac = 'someRandomHMAC';

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);
        $registration->expects($this->any())->method('getUid')->will($this->returnValue($regUid));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->once())->method('getMaxParticipants')->will($this->returnValue(10));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('add'), array(), '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            array('persistAll'), array(), '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            array('clearCacheForConfiguredUids'), array(), '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('generateHmac'), array(), '', false);
        $hashService->expects($this->once())->method('generateHmac')->will($this->returnValue($regHmac));
        $this->inject($this->subject, 'hashService', $hashService);

        // Inject settings so autoconfirmation is disabled
        $settings = array(
            'registration' => array(
                'autoConfirmation' => 1
            )
        );
        $this->inject($this->subject, 'settings', $settings);

        $this->subject->expects($this->once())->method('redirect')->with('confirmRegistration', null, null,
            array('reguid' => $regUid, 'hmac' => $regHmac));

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
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);
        $registration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(2));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getMaxParticipants')->will($this->returnValue(10));
        $event->expects($this->any())->method('getFreePlaces')->will($this->returnValue(10));
        $event->expects($this->any())->method('getMaxRegistrationsPerUser')->will($this->returnValue(2));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('add'), array(), '', false);
        $registrationRepository->expects($this->once())->method('add');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $notificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            array(), array(), '', false);
        $notificationService->expects($this->once())->method('sendUserMessage');
        $notificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $notificationService);

        $persistenceManager = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager',
            array('persistAll'), array(), '', false);
        $persistenceManager->expects($this->once())->method('persistAll');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array('get'), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($persistenceManager));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $utilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            array('clearCacheForConfiguredUids'), array(), '', false);
        $utilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $utilityService);

        $registrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('createDependingRegistrations'), array(), '', false);
        $registrationService->expects($this->once())->method('createDependingRegistrations')->with($registration);
        $this->inject($this->subject, 'registrationService', $registrationService);

        $this->subject->expects($this->once())->method('redirect')->with('saveRegistrationResult', null, null,
            array('result' => RegistrationResult::REGISTRATION_SUCCESSFUL));

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

        $returnedArray = array(
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        );

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('checkConfirmRegistration'), array(), '', false);
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

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->once())->method('setConfirmed')->with(true);
        $mockRegistration->expects($this->once())->method('getAmountOfRegistrations')->will($this->returnValue(2));

        $returnedArray = array(
            false,
            $mockRegistration,
            'event.message.confirmation_successful',
            'confirmRegistration.title.successful'
        );

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('checkConfirmRegistration', 'confirmDependingRegistrations'), array(), '', false);
        $mockRegistrationService->expects($this->once())->method('checkConfirmRegistration')->will($this->returnValue($returnedArray));
        $mockRegistrationService->expects($this->once())->method('confirmDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            array(), array(), '', false);
        $mockNotificationService->expects($this->once())->method('sendUserMessage');
        $mockNotificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('update'), array(), '', false);
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

        $returnedArray = array(
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        );

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('checkCancelRegistration'), array(), '', false);
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

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(2));

        $returnedArray = array(
            false,
            $mockRegistration,
            'event.message.cancel_successful',
            'cancelRegistration.title.successful'
        );

        $mockRegistrationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\RegistrationService',
            array('checkCancelRegistration', 'cancelDependingRegistrations'), array(), '', false);
        $mockRegistrationService->expects($this->once())->method('checkCancelRegistration')->will($this->returnValue($returnedArray));
        $mockRegistrationService->expects($this->once())->method('cancelDependingRegistrations')->with($mockRegistration);
        $this->inject($this->subject, 'registrationService', $mockRegistrationService);

        $mockNotificationService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\NotificationService',
            array(), array(), '', false);
        $mockNotificationService->expects($this->once())->method('sendUserMessage');
        $mockNotificationService->expects($this->once())->method('sendAdminMessage');
        $this->inject($this->subject, 'notificationService', $mockNotificationService);

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('remove'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('remove');
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockUtilityService = $this->getMock('DERHANSEN\\SfEventMgt\\Service\\UtilityService',
            array('clearCacheForConfiguredUids'), array(), '', false);
        $mockUtilityService->expects($this->once())->method('clearCacheForConfiguredUids');
        $this->inject($this->subject, 'utilityService', $mockUtilityService);

        $this->subject->cancelRegistrationAction(1, 'VALID-HMAC');
    }
}
