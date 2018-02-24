<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Service\PaymentService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\RegistrationService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationServiceTest extends UnitTestCase
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
            ['setHidden'], [], '', false);
        $registration->expects($this->once())->method('setHidden')->with(true);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($registration);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findExpiredRegistrations', 'update'], [], '', false);
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
            [], [], '', false);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($registration);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findExpiredRegistrations', 'remove'], [], '', false);
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
            [], [], '', false);
        $mockRegistration->expects($this->any())->method('getAmountOfRegistrations')->will($this->returnValue(5));

        $newRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            [], [], '', false);
        $newRegistration->expects($this->any())->method('setMainRegistration');
        $newRegistration->expects($this->any())->method('setAmountOfRegistrations');
        $newRegistration->expects($this->any())->method('setIgnoreNotifications');

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            [], [], '', false);
        $objectManager->expects($this->any())->method('get')->will($this->returnValue($newRegistration));
        $this->inject($this->subject, 'objectManager', $objectManager);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['add'], [], '', false);
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
            [], [], '', false);

        $foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            [], [], '', false);
        $foundRegistration1->expects($this->any())->method('setConfirmed');

        $foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            [], [], '', false);
        $foundRegistration2->expects($this->any())->method('setConfirmed');

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByMainRegistration', 'update'], [], '', false);
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
            [], [], '', false);

        $foundRegistration1 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            [], [], '', false);

        $foundRegistration2 = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            [], [], '', false);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByMainRegistration', 'remove'], [], '', false);
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
            ['validateHmac'], [], '', false);
        $hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(false));
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        ];
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
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.confirmation_failed_registration_not_found',
            'confirmRegistration.title.failed'
        ];
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

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_confirmation_until_expired',
            'confirmRegistration.title.failed'
        ];
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

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getConfirmationUntil')->will($this->returnValue(new \DateTime('tomorrow')));
        $mockRegistration->expects($this->any())->method('getConfirmed')->will($this->returnValue(true));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_already_confirmed',
            'confirmRegistration.title.failed'
        ];
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
            ['validateHmac'], [], '', false);
        $hashService->expects($this->once())->method('validateHmac')->will($this->returnValue(false));
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        ];
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
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.cancel_failed_registration_not_found_or_cancelled',
            'cancelRegistration.title.failed'
        ];
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

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(false));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_cancel_disabled',
            'cancelRegistration.title.failed'
        ];
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

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.cancel_failed_deadline_expired',
            'cancelRegistration.title.failed'
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if event startdate passed
     *
     * @test
     * @return void
     */
    public function checkCancelRegistrationIfEventStartdatePassedTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $mockEvent->expects($this->any())->method('getEnableCancel')->will($this->returnValue(true));
        $mockEvent->expects($this->any())->method('getCancelDeadline')->will($this->returnValue(new \DateTime('tomorrow')));
        $mockEvent->expects($this->any())->method('getStartdate')->will($this->returnValue(new \DateTime('yesterday')));

        $mockRegistration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [], [], '',
            false);
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));

        $mockRegistrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findByUid'], [], '', false);
        $mockRegistrationRepository->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($mockRegistration));
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\\HashService',
            ['validateHmac'], [], '', false);
        $mockHashService->expects($this->once())->method('validateHmac')->will($this->returnValue(true));
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.cancel_failed_event_started',
            'cancelRegistration.title.failed'
        ];
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
        $GLOBALS['TSFE']->fe_user->user = [];
        $GLOBALS['TSFE']->fe_user->user['uid'] = 1;

        $feUser = new \TYPO3\CMS\Extbase\Domain\Model\FrontendUser();

        $mockFeUserRepository = $this->getMock('TYPO3\\CMS\\Extbase\\Domain\\Repository\\FrontendUserRepository', [], [], '', false);
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
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
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
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
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
        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES);
    }

    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfUniqueEmailCheckEnabledAndEmailRegisteredToEvent()
    {
        $repoRegistrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $repoRegistrations->expects($this->any())->method('count')->will($this->returnValue(10));

        $registrationRepository = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Repository\\RegistrationRepository',
            ['findEventRegistrationsByEmail'], [], '', false);
        $registrationRepository->expects($this->once())->method('findEventRegistrationsByEmail')->will(
            $this->returnValue($repoRegistrations));
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $registration = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration', [],
            [], '', false);
        $registration->expects($this->any())->method('getEmail')->will($this->returnValue('email@domain.tld'));

        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(1));

        $event = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', [], [], '', false);
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects($this->once())->method('getEnableRegistration')->will($this->returnValue(true));
        $event->expects($this->once())->method('getStartdate')->will($this->returnValue($startdate));
        $event->expects($this->any())->method('getRegistration')->will($this->returnValue($registrations));
        $event->expects($this->any())->method('getUniqueEmailCheck')->will($this->returnValue(true));

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertFalse($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE);
    }

    /**
     * @test
     * @return void
     */
    public function checkRegistrationSuccessFailsIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
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

        $success = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        $this->assertTrue($success);
        $this->assertEquals($result, RegistrationResult::REGISTRATION_SUCCESSFUL);
    }

    /**
     * @test
     * @return void
     */
    public function redirectPaymentEnabledReturnsFalseIfPaymentNotEnabled()
    {
        $event = new Event();
        $event->setEnablePayment(false);

        $mockRegistration = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            ['getEvent'],
            [],
            '',
            false
        );
        $mockRegistration->expects($this->once())->method('getEvent')->will($this->returnValue($event));

        $this->assertFalse($this->subject->redirectPaymentEnabled($mockRegistration));
    }

    /**
     * @test
     * @return void
     */
    public function redirectPaymentEnabledReturnsTrueIfPaymentRedirectEnabled()
    {
        $event = new Event();
        $event->setEnablePayment(true);

        $mockRegistration = $this->getMock(
            'DERHANSEN\\SfEventMgt\\Domain\\Model\\Registration',
            ['getEvent', 'getPaymentMethod'],
            [],
            '',
            false
        );
        $mockRegistration->expects($this->once())->method('getEvent')->will($this->returnValue($event));
        $mockRegistration->expects($this->once())->method('getPaymentMethod');

        // Payment mock object with redirect enabled
        $mockInvoice = $this->getMock('DERHANSEN\\SfEventMgt\\Payment\\Invoice', ['isRedirectEnabled'], [], '', false);
        $mockInvoice->expects($this->once())->method('isRedirectEnabled')->will($this->returnValue(true));

        $mockPaymentService = $this->getMock(
            PaymentService::class,
            ['getPaymentInstance'],
            [],
            '',
            false
        );
        $mockPaymentService->expects($this->once())->method('getPaymentInstance')->will($this->returnValue($mockInvoice));
        $this->inject($this->subject, 'paymentService', $mockPaymentService);

        $this->assertTrue($this->subject->redirectPaymentEnabled($mockRegistration));
    }

    /**
     * @test
     * @return void
     */
    public function isWaitlistRegistrationReturnsFalseIfEventNotFullyBookedAndEnoughFreePlaces()
    {
        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(5));

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        $this->assertFalse($this->subject->isWaitlistRegistration($event, 3));
    }

    /**
     * @test
     * @return void
     */
    public function isWaitlistRegistrationReturnsTrueIfEventNotFullyBookedAndNotEnoughFreePlaces()
    {
        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(9));

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        $this->assertTrue($this->subject->isWaitlistRegistration($event, 2));
    }

    /**
     * @test
     * @return void
     */
    public function isWaitlistRegistrationReturnsTrueIfEventFullyBookedAndNotEnoughFreePlaces()
    {
        $registrations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', [], [], '', false);
        $registrations->expects($this->any())->method('count')->will($this->returnValue(11));

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        $this->assertTrue($this->subject->isWaitlistRegistration($event, 1));
    }

}
