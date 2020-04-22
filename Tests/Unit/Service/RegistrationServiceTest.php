<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Payment\Invoice;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

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
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new RegistrationService();
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
    public function createDependingRegistrationsCreatesAmountOfExpectedRegistrations()
    {
        $this->markTestSkipped('Needs investigation');
        $mockRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $mockRegistration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(5);
        $mockRegistration->expects(self::any())->method('getPid')->willReturn(1);

        $newRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $newRegistration->expects(self::any())->method('setMainRegistration');
        $newRegistration->expects(self::any())->method('setAmountOfRegistrations');
        $newRegistration->expects(self::any())->method('setIgnoreNotifications');

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::any())->method('get')->willReturn($newRegistration);
        $this->inject($this->subject, 'objectManager', $objectManager);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::exactly(4))->method('add')->with($newRegistration);
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->createDependingRegistrations($mockRegistration);
    }

    /**
     * @test
     */
    public function confirmDependingRegistrationsConfirmsDependingRegistrations()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();

        $foundRegistration1 = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $foundRegistration1->expects(self::any())->method('setConfirmed');

        $foundRegistration2 = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $foundRegistration2->expects(self::any())->method('setConfirmed');

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByMainRegistration', 'update'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findByMainRegistration')->willReturn($registrations);
        $registrationRepository->expects(self::exactly(2))->method('update');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->confirmDependingRegistrations($mockRegistration);
    }

    /**
     * @test
     */
    public function cancelDependingRegistrationsRemovesDependingRegistrations()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();

        $foundRegistration1 = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $foundRegistration2 = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new ObjectStorage();
        $registrations->attach($foundRegistration1);
        $registrations->attach($foundRegistration2);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByMainRegistration', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findByMainRegistration')->willReturn($registrations);
        $registrationRepository->expects(self::exactly(2))->method('remove');
        $this->inject($this->subject, 'registrationRepository', $registrationRepository);

        $this->subject->cancelDependingRegistrations($mockRegistration);
    }

    /**
     * Test if expected array is returned if HMAC validations fails
     *
     * @test
     */
    public function checkConfirmRegistrationIfHmacValidationFailsTest()
    {
        $reguid = 1;
        $hmac = 'invalid-hmac';

        $hashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $hashService->expects(self::once())->method('validateHmac')->willReturn(false);
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if no Registration found
     *
     * @test
     */
    public function checkConfirmRegistrationIfNoRegistrationTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.confirmation_failed_registration_not_found',
            'confirmRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if confirmation date expired
     *
     * @test
     */
    public function checkConfirmRegistrationIfConfirmationDateExpiredTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $mockRegistration->expects(self::any())->method('getConfirmationUntil')->willReturn(new \DateTime('yesterday'));

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($mockRegistration);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_confirmation_until_expired',
            'confirmRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if registration already confirmed
     *
     * @test
     */
    public function checkConfirmRegistrationIfRegistrationConfirmedTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistration = $this->getMockBuilder(Registration::class)->disableOriginalConstructor()->getMock();
        $mockRegistration->expects(self::any())->method('getConfirmationUntil')->willReturn(new \DateTime('tomorrow'));
        $mockRegistration->expects(self::any())->method('getConfirmed')->willReturn(true);

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($mockRegistration);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkConfirmRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_already_confirmed',
            'confirmRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if HMAC validations fails
     *
     * @test
     */
    public function checkCancelRegistrationIfHmacValidationFailsTest()
    {
        $reguid = 1;
        $hmac = 'invalid-hmac';

        $hashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $hashService->expects(self::once())->method('validateHmac')->willReturn(false);
        $this->inject($this->subject, 'hashService', $hashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if no Registration found
     *
     * @test
     */
    public function checkCancelRegistrationIfNoRegistrationTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            null,
            'event.message.cancel_failed_registration_not_found_or_cancelled',
            'cancelRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if cancellation is not enabled
     *
     * @test
     */
    public function checkCancelRegistrationIfCancellationIsNotEnabledTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getEnableCancel')->willReturn(false);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($mockRegistration);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.confirmation_failed_cancel_disabled',
            'cancelRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if cancellation deadline expired
     *
     * @test
     */
    public function checkCancelRegistrationIfCancellationDeadlineExpiredTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getEnableCancel')->willReturn(true);
        $mockEvent->expects(self::any())->method('getCancelDeadline')->willReturn(new \DateTime('yesterday'));

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($mockRegistration);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.cancel_failed_deadline_expired',
            'cancelRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected array is returned if event startdate passed
     *
     * @test
     */
    public function checkCancelRegistrationIfEventStartdatePassedTest()
    {
        $reguid = 1;
        $hmac = 'valid-hmac';

        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getEnableCancel')->willReturn(true);
        $mockEvent->expects(self::any())->method('getCancelDeadline')->willReturn(new \DateTime('tomorrow'));
        $mockEvent->expects(self::any())->method('getStartdate')->willReturn(new \DateTime('yesterday'));

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);

        $mockRegistrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($mockRegistration);
        $this->inject($this->subject, 'registrationRepository', $mockRegistrationRepository);

        $mockHashService = $this->getMockBuilder(HashService::class)
            ->setMethods(['validateHmac'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockHashService->expects(self::once())->method('validateHmac')->willReturn(true);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $result = $this->subject->checkCancelRegistration($reguid, $hmac);
        $expected = [
            true,
            $mockRegistration,
            'event.message.cancel_failed_event_started',
            'cancelRegistration.title.failed'
        ];
        self::assertEquals($expected, $result);
    }

    /**
     * Test if expected value is returned if no frontend user logged in
     *
     * @test
     */
    public function getCurrentFeUserObjectReturnsNullIfNoFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = null;
        self::assertNull($this->subject->getCurrentFeUserObject());
    }

    /**
     * Test if expected value is returned if a frontend user logged in
     *
     * @test
     */
    public function getCurrentFeUserObjectReturnsFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [];
        $GLOBALS['TSFE']->fe_user->user['uid'] = 1;

        $feUser = new FrontendUser();

        $mockFeUserRepository = $this->getMockBuilder(FrontendUserRepository::class)
            ->setMethods(['findByUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFeUserRepository->expects(self::once())->method('findByUid')->with(1)->willReturn($feUser);
        $this->inject($this->subject, 'frontendUserRepository', $mockFeUserRepository);

        self::assertEquals($this->subject->getCurrentFeUserObject(), $feUser);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfRegistrationNotEnabled()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();

        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(false);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_NOT_ENABLED);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfRegistrationDeadlineExpired()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();

        $event = $this->getMockBuilder(Event::class)->getMock();
        $deadline = new \DateTime();
        $deadline->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::any())->method('getRegistrationDeadline')->willReturn($deadline);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfEventExpired()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('yesterday'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfMaxParticipantsReached()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();

        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::once())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::once())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfAmountOfRegistrationsGreaterThanRemainingPlaces()
    {
        $registration = $this->getMockBuilder(Registration::class)
            ->setMethods(['getAmountOfRegistrations'])
            ->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(11);

        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(10);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getFreePlaces')->willReturn(10);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(20);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfUniqueEmailCheckEnabledAndEmailRegisteredToEvent()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getEmail')->willReturn('email@domain.tld');

        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(1);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getUniqueEmailCheck')->willReturn(true);

        $mockRegistrationService = $this->getMockBuilder(RegistrationService::class)
            ->setMethods(['emailNotUnique'])
            ->getMock();
        $mockRegistrationService->expects(self::once())->method('emailNotUnique')->willReturn(true);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $mockRegistrationService->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessFailsIfAmountOfRegistrationsExceedsMaxAmountOfRegistrations()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();
        $registration->expects(self::any())->method('getAmountOfRegistrations')->willReturn(6);

        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
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

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertFalse($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED);
    }

    /**
     * @test
     */
    public function checkRegistrationSuccessSucceedsWhenAllConditionsMet()
    {
        $registration = $this->getMockBuilder(Registration::class)->getMock();

        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = $this->getMockBuilder(Event::class)->getMock();
        $startdate = new \DateTime();
        $startdate->add(\DateInterval::createFromDateString('tomorrow'));
        $event->expects(self::once())->method('getEnableRegistration')->willReturn(true);
        $event->expects(self::once())->method('getStartdate')->willReturn($startdate);
        $event->expects(self::any())->method('getRegistrations')->willReturn($registrations);
        $event->expects(self::any())->method('getMaxParticipants')->willReturn(10);

        $result = RegistrationResult::REGISTRATION_SUCCESSFUL;
        list($success, $result) = $this->subject->checkRegistrationSuccess($event, $registration, $result);
        self::assertTrue($success);
        self::assertEquals($result, RegistrationResult::REGISTRATION_SUCCESSFUL);
    }

    /**
     * @test
     */
    public function redirectPaymentEnabledReturnsFalseIfPaymentNotEnabled()
    {
        $event = new Event();
        $event->setEnablePayment(false);

        $mockRegistration = $this->getMockBuilder(Registration::class)->setMethods(['getEvent'])->getMock();
        $mockRegistration->expects(self::once())->method('getEvent')->willReturn($event);

        self::assertFalse($this->subject->redirectPaymentEnabled($mockRegistration));
    }

    /**
     * @test
     */
    public function redirectPaymentEnabledReturnsTrueIfPaymentRedirectEnabled()
    {
        $event = new Event();
        $event->setEnablePayment(true);

        $mockRegistration = $this->getMockBuilder(Registration::class)
            ->setMethods(['getEvent', 'getPaymentMethod'])
            ->getMock();
        $mockRegistration->expects(self::once())->method('getEvent')->willReturn($event);
        $mockRegistration->expects(self::once())->method('getPaymentMethod');

        // Payment mock object with redirect enabled
        $mockInvoice = $this->getMockBuilder(Invoice::class)
            ->setMethods(['isRedirectEnabled'])
            ->getMock();
        $mockInvoice->expects(self::once())->method('isRedirectEnabled')->willReturn(true);

        $mockPaymentService = $this->getMockBuilder(PaymentService::class)
            ->setMethods(['getPaymentInstance'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockPaymentService->expects(self::once())->method('getPaymentInstance')->willReturn($mockInvoice);
        $this->inject($this->subject, 'paymentService', $mockPaymentService);

        self::assertTrue($this->subject->redirectPaymentEnabled($mockRegistration));
    }

    /**
     * @test
     */
    public function isWaitlistRegistrationReturnsFalseIfEventNotFullyBookedAndEnoughFreePlaces()
    {
        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(5);

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        self::assertFalse($this->subject->isWaitlistRegistration($event, 3));
    }

    /**
     * @test
     */
    public function isWaitlistRegistrationReturnsTrueIfEventNotFullyBookedAndNotEnoughFreePlaces()
    {
        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(9);

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        self::assertTrue($this->subject->isWaitlistRegistration($event, 2));
    }

    /**
     * @test
     */
    public function isWaitlistRegistrationReturnsTrueIfEventFullyBookedAndNotEnoughFreePlaces()
    {
        $registrations = $this->getMockBuilder(ObjectStorage::class)
            ->setMethods(['count'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrations->expects(self::any())->method('count')->willReturn(11);

        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(10);
        $event->setRegistration($registrations);

        self::assertTrue($this->subject->isWaitlistRegistration($event, 1));
    }
}
