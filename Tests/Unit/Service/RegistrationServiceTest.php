<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

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
 * Test case for class DERHANSEN\SfEventMgt\Service\RegistrationService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Service\RegistrationService();
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
     */
    public function handleExpiredRegistrationsWithoutDeleteOption()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array('setHidden'), array(), '', false);
        $registration->expects($this->once())->method('setHidden')->with(true);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($registration);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findExpiredRegistrations', 'update'), array(), '', false);
        $registrationRepository->expects($this->once())->method('findExpiredRegistrations')->will(
            $this->returnValue($registrations));
        $registrationRepository->expects($this->once())->method('update');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->handleExpiredRegistrations();
    }

    /**
     * @test
     */
    public function handleExpiredRegistrationsWithDeleteOption()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($registration);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findExpiredRegistrations', 'remove'), array(), '', false);
        $registrationRepository->expects($this->once())->method('findExpiredRegistrations')->will(
            $this->returnValue($registrations));
        $registrationRepository->expects($this->once())->method('remove');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->handleExpiredRegistrations(true);
    }

    /**
     * @test
     */
    public function createDependingRegistrationsCreatesAmountOfExpectedRegistrations()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);
        $mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(5));

        $newRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);
        $newRegistration->expects($this->any())->method('setMainRegistration');
        $newRegistration->expects($this->any())->method('setAmountOfRegistrations');
        $newRegistration->expects($this->any())->method('setIgnoreNotifications');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            array(), array(), '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($newRegistration));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('add'), array(), '', false);
        $registrationRepository->expects($this->exactly(4))->method('add')->with($newRegistration);
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->createDependingRegistrations($mockRegistration);
    }

    /**
     * @test
     */
    public function confirmDependingRegistrationsConfirmsDependingRegistrations()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);

        $foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);
        $foundRegistration1->expects($this->any())->method('setConfirmed');

        $foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);
        $foundRegistration2->expects($this->any())->method('setConfirmed');

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByMainRegistration', 'update'), array(), '', false);
        $registrationRepository->expects($this->once())->method('findByMainRegistration')->will($this->returnValue($registrations));
        $registrationRepository->expects($this->exactly(2))->method('update');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->confirmDependingRegistrations($mockRegistration);
    }

    /**
     * @test
     */
    public function cancelDependingRegistrationsRemovesDependingRegistrations()
    {
        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);

        $foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);

        $foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            array(), array(), '', false);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByMainRegistration', 'remove'), array(), '', false);
        $registrationRepository->expects($this->once())->method('findByMainRegistration')->will($this->returnValue($registrations));
        $registrationRepository->expects($this->exactly(2))->method('remove');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->cancelDependingRegistrations($mockRegistration);
    }

    /**
     * Test if expected array is returned if HMAC validations fails
     *
     * @test
     * @return void
     */
    public function checkConfirmRegistrationIfHmacValidationFailsTest()
    {
        $reguid = 1;
        $hmac = 'invalid-hmac';

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(false));
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = array(
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if no Registration found
     *
     * @test
     * @return void
     */
    public function checkConfirmRegistrationIfNoRegistrationTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = array(
            true,
            null,
            'event.message.confirmation_failed_registration_not_found',
            'confirmRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if confirmation date expired
     *
     * @test
     * @return void
     */
    public function checkConfirmRegistrationIfConfirmationDateExpiredTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = array(
            true,
            $mockRegistration,
            'event.message.confirmation_failed_confirmation_until_expired',
            'confirmRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if registration already confirmed
     *
     * @test
     * @return void
     */
    public function checkConfirmRegistrationIfRegistrationConfirmedTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('tomorrow')));
        $mockRegistration->expects($this->any())->method('getConfirmed')->will($this->returnValue(true));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = array(
            true,
            $mockRegistration,
            'event.message.confirmation_failed_already_confirmed',
            'confirmRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if HMAC validations fails
     *
     * @test
     * @return void
     */
    public function checkCancelRegistrationIfHmacValidationFailsTest()
    {
        $reguid = 1;
        $hmac = 'invalid-hmac';

        $hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(false));
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = array(
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if no Registration found
     *
     * @test
     * @return void
     */
    public function checkCancelRegistrationIfNoRegistrationTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = array(
            true,
            null,
            'event.message.cancel_failed_registration_not_found_or_cancelled',
            'cancelRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if cancellation is not enabled
     *
     * @test
     * @return void
     */
    public function checkCancelRegistrationIfCancellationIsNotEnabledTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(false));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = array(
            true,
            $mockRegistration,
            'event.message.confirmation_failed_cancel_disabled',
            'cancelRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if cancellation deadline expired
     *
     * @test
     * @return void
     */
    public function checkCancelRegistrationIfCancellationDeadlineExpiredTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(), array(), '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            array('findByUid'), array(), '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            array('validateHmac'), array(), '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = array(
            true,
            $mockRegistration,
            'event.message.cancel_failed_deadline_expired',
            'cancelRegistration.title.failed'
        );
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected value is returned if no frontend user logged in
     *
     * @test
     * @return void
     */
    public function getCurrentFeUserObjectReturnsNullIfNoFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = null;
        $this->assertNull($this->subject->getCurrentFeUserObject());
    }

    /**
     * Test if expected value is returned if a frontend user logged in
     *
     * @test
     * @return void
     */
    public function getCurrentFeUserObjectReturnsFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = array();
        $GLOBALS['TSFE']->fe_user->user['uid'] = 1;

        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $mockFeUserRepository = $this->getMock('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository', array(), array(), '', false);
        $mockFeUserRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($feUser));
        $this->inject($this->subject, 'frontendUserRepository', $mockFeUserRepository);

        $this->assertEquals($this->subject->getCurrentFeUserObject(), $feUser);
    }

    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfRegistrationNotEnabled()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(false));

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_NOT_ENABLED);
    }

    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfRegistrationDeadlineExpired()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->any())->method('getRegistrationDeadline')->will($this->returnValue($deadline));

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED);
    }

    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfEventExpired()
    {
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', array(),
            array(), '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array(), array(), '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED);
    }


    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfMaxParticipantsReached()
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS);
    }


    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfAmountOfRegistrationsGreaterThanRemainingPlaces()
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES);
    }


    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED);
    }


    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessSucceedsWhenAllConditionsMet()
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertTrue($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_SUCCESSFUL);

    }
}
