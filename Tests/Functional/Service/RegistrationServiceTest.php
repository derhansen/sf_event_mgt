<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\FrontendUserRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use DERHANSEN\SfEventMgt\Utility\RegistrationResult;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RegistrationServiceTest extends FunctionalTestCase
{
    protected PersistenceManager $persistentManager;
    protected RegistrationRepository $registrationRepository;
    protected RegistrationService $subject;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected function setUp(): void
    {
        parent::setUp();

        $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] = 'foo';

        $this->persistentManager = $this->get(PersistenceManager::class);
        $this->registrationRepository = $this->get(RegistrationRepository::class);
        $GLOBALS['BE_USER'] = new BackendUserAuthentication();

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $this->get(ConfigurationManagerInterface::class)->setRequest($request);

        $context = $this->get(Context::class);
        $eventDispatcher = $this->get(EventDispatcherInterface::class);
        $registrationRepository = $this->registrationRepository;
        $hashService = $this->get(HashService::class);
        $frontendUserRepository = $this->get(FrontendUserRepository::class);
        $paymentService = $this->get(PaymentService::class);
        $notificationService = $this->get(NotificationService::class);

        $this->subject = new RegistrationService(
            $context,
            $eventDispatcher,
            $registrationRepository,
            $frontendUserRepository,
            $hashService,
            $paymentService,
            $notificationService
        );
    }

    #[Test]
    public function createDependingRegistrationsCreatesDependingRegistrations(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/create_depending_registrations.csv');

        /** @var Registration $registration */
        $registration = $this->registrationRepository->findByUid(1);
        $registration->setAmountOfRegistrations(3); // The test creates 2 additional registrations

        $this->subject->createDependingRegistrations($registration);

        $this->persistentManager->persistAll();

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/TestResults/create_depending_registrations.csv');
    }

    #[Test]
    public function confirmDependingRegistrationsConfirmsDependingRegistrations(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/confirm_depending_registrations.csv');

        /** @var Registration $registration */
        $registration = $this->registrationRepository->findByUid(1);

        $this->subject->confirmDependingRegistrations($registration);

        $this->persistentManager->persistAll();

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/TestResults/confirm_depending_registrations.csv');
    }

    #[Test]
    public function cancel0DependingRegistrationsCancelsDependingRegistrations(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/cancel_depending_registrations.csv');

        /** @var Registration $registration */
        $registration = $this->registrationRepository->findByUid(1);

        $this->subject->cancelDependingRegistrations($registration);

        $this->persistentManager->persistAll();

        $this->assertCSVDataSet(__DIR__ . '/../Fixtures/TestResults/cancel_depending_registrations.csv');
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayOnHmacError(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $expected = [
            true,
            null,
            'event.message.confirmation_failed_wrong_hmac',
            'confirmRegistration.title.failed',
        ];

        $result = $this->subject->checkConfirmRegistration(1, 'invalid-hmac');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfRegistrationNotFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $expected = [
            true,
            null,
            'event.message.confirmation_failed_registration_not_found',
            'confirmRegistration.title.failed',
        ];

        $result = $this->subject->checkConfirmRegistration(1111, '551d03160f966d25e4730750fd87ed4dc08f73d6');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfRegistrationEventNotFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $registration = $this->registrationRepository->findByUid(2);

        $expected = [
            true,
            $registration,
            'event.message.confirmation_failed_registration_event_not_found',
            'confirmRegistration.title.failed',
        ];

        $result = $this->subject->checkConfirmRegistration(2, 'fbc9ad05a7d3501a0da052092f17a7066694a00a');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfConfirmationDateExpired(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $registration = $this->registrationRepository->findByUid(3);

        $expected = [
            true,
            $registration,
            'event.message.confirmation_failed_confirmation_until_expired',
            'confirmRegistration.title.failed',
        ];

        $result = $this->subject->checkConfirmRegistration(3, '30d637a3e2b168bc798cfc2a155cd1bb6dfc68f5');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfRegistrationAlreadyConfirmed(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $registration = $this->registrationRepository->findByUid(1);

        $expected = [
            true,
            $registration,
            'event.message.confirmation_failed_already_confirmed',
            'confirmRegistration.title.failed',
        ];

        $result = $this->subject->checkConfirmRegistration(1, '824c574f7a593ccf46aa17ae72caec5de8f98a96');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfWaitlistRegistrationConfirmed(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $registration = $this->registrationRepository->findByUid(4);

        $expected = [
            false,
            $registration,
            'event.message.confirmation_waitlist_successful',
            'confirmRegistrationWaitlist.title.successful',
        ];

        $result = $this->subject->checkConfirmRegistration(4, '6048f8069814fa6a119b3121238da8a14f8997c7');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkConfirmRegistrationReturnsExpectedArrayIfRegistrationConfirmed(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $registration = $this->registrationRepository->findByUid(5);

        $expected = [
            false,
            $registration,
            'event.message.confirmation_successful',
            'confirmRegistration.title.successful',
        ];

        $result = $this->subject->checkConfirmRegistration(5, '72cd48ac74a4d482c71799c85879c1f712a04e59');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayOnHmacError(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_confirm_registration.csv');

        $expected = [
            true,
            null,
            'event.message.cancel_failed_wrong_hmac',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(1, 'invalid-hmac');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfRegistrationNotFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $expected = [
            true,
            null,
            'event.message.cancel_failed_registration_not_found_or_cancelled',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(1111, '551d03160f966d25e4730750fd87ed4dc08f73d6');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfRegistrationEventNotFound(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $registration = $this->registrationRepository->findByUid(2);

        $expected = [
            true,
            $registration,
            'event.message.cancel_failed_event_not_found',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(2, 'fbc9ad05a7d3501a0da052092f17a7066694a00a');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfCancellationNotEnabled(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $registration = $this->registrationRepository->findByUid(3);

        $expected = [
            true,
            $registration,
            'event.message.cancel_failed_cancel_disabled',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(3, '30d637a3e2b168bc798cfc2a155cd1bb6dfc68f5');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfCancellationDeadlineExpired(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $registration = $this->registrationRepository->findByUid(4);

        $expected = [
            true,
            $registration,
            'event.message.cancel_failed_deadline_expired',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(4, '6048f8069814fa6a119b3121238da8a14f8997c7');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfCancelFailedEventStarted(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $registration = $this->registrationRepository->findByUid(5);

        $expected = [
            true,
            $registration,
            'event.message.cancel_failed_event_started',
            'cancelRegistration.title.failed',
        ];

        $result = $this->subject->checkCancelRegistration(5, '72cd48ac74a4d482c71799c85879c1f712a04e59');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function checkCancelRegistrationReturnsExpectedArrayIfRegistrationCancelled(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_cancel_registration.csv');

        $registration = $this->registrationRepository->findByUid(1);

        $expected = [
            false,
            $registration,
            'event.message.cancel_successful',
            'cancelRegistration.title.successful',
        ];

        $result = $this->subject->checkCancelRegistration(1, '824c574f7a593ccf46aa17ae72caec5de8f98a96');
        self::assertEquals($expected, $result);
    }

    #[Test]
    public function getCurrentFeUserObjectReturnsNullIfNoFrontendUser(): void
    {
        self::assertNull($this->subject->getCurrentFeUserObject());
    }

    #[Test]
    public function getCurrentFeUserObjectReturnsFrontendUserObject(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/get_current_fe_user_object.csv');

        $user = new FrontendUserAuthentication();
        $user->user['uid'] = 1;

        $this->get(Context::class)->setAspect(
            'frontend.user',
            GeneralUtility::makeInstance(UserAspect::class, $user)
        );

        $result = $this->subject->getCurrentFeUserObject();
        self::assertInstanceOf(FrontendUser::class, $result);
        self::assertEquals('testuser', $result->getUsername());
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfRegistrationNotEnabled(): void
    {
        $event = new Event();
        $event->setEnableRegistration(false);

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_NOT_ENABLED,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfRegistrationDeadlineExpired(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setRegistrationDeadline((new \DateTime())->modify('-1 day'));

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_DEADLINE_EXPIRED,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfEventStartedAndRegistrationAfterStartIsDisabled(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate((new \DateTime())->modify('-1 day'));
        $event->setEnddate((new \DateTime())->modify('+1 day'));
        $event->setAllowRegistrationUntilEnddate(false);

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_EVENT_EXPIRED,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfEventEndedAndRegistrationAfterStartIsEnabled(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate((new \DateTime())->modify('-2 day'));
        $event->setEnddate((new \DateTime())->modify('-1 day'));
        $event->setAllowRegistrationUntilEnddate(true);

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_EVENT_ENDED,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfMaxParticipantsExpiredAndNoWaitlist(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate((new \DateTime())->modify('+2 day'));
        $event->setEnddate((new \DateTime())->modify('+3 day'));
        $event->setEnableWaitlist(false);
        $event->setMaxParticipants(1);
        $event->addRegistration(new Registration());

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_MAX_PARTICIPANTS,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfAmountOfRegistrationExceedsFreePlaces(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate((new \DateTime())->modify('+2 day'));
        $event->setEnddate((new \DateTime())->modify('+3 day'));
        $event->setEnableWaitlist(false);
        $event->setMaxParticipants(3);
        $event->addRegistration(new Registration());

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');
        $registration->setAmountOfRegistrations(3);

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfAmountOfRegistrationExceedsMaxAmountOfRegistrationsPerUser(): void
    {
        $event = new Event();
        $event->setEnableRegistration(true);
        $event->setStartdate((new \DateTime())->modify('+2 day'));
        $event->setEnddate((new \DateTime())->modify('+3 day'));
        $event->setEnableWaitlist(false);
        $event->setMaxParticipants(3);
        $event->setMaxRegistrationsPerUser(1);

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('email@domain.tld');
        $registration->setAmountOfRegistrations(2);

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsFalseIfUniqueEmailCheckActiveAndEmailAlreadyRegistered(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_success.csv');

        /** @var Registration $existingRegistration */
        $existingRegistration = $this->registrationRepository->findByUid(1);
        $event = $existingRegistration->getEvent();

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('reg1@domain.tld');

        $expected = [
            false,
            RegistrationResult::REGISTRATION_FAILED_EMAIL_NOT_UNIQUE,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsTrueIfRegistrationOnWaitlistSuccessful(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_success.csv');

        /** @var Registration $existingRegistration */
        $existingRegistration = $this->registrationRepository->findByUid(2);
        $event = $existingRegistration->getEvent();

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('reg1@domain.tld');

        $expected = [
            true,
            RegistrationResult::REGISTRATION_SUCCESSFUL_WAITLIST,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function checkRegistrationSuccessReturnsTrueIfRegistrationSuccessful(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_success.csv');

        /** @var Registration $existingRegistration */
        $existingRegistration = $this->registrationRepository->findByUid(1);
        $event = $existingRegistration->getEvent();

        $registration = new Registration();
        $registration->setFirstname('Firstname');
        $registration->setLastname('Lastname');
        $registration->setEmail('reg2@domain.tld');

        $expected = [
            true,
            RegistrationResult::REGISTRATION_SUCCESSFUL,
        ];
        self::assertEquals($expected, $this->subject->checkRegistrationSuccess($event, $registration));
    }

    #[Test]
    public function redirectPaymentEnabledReturnsFalseIfPaymentNotEnabledforEvent(): void
    {
        $event = new Event();
        $registration = new Registration();
        $registration->setEvent($event);

        self::assertFalse($this->subject->redirectPaymentEnabled($registration));
    }

    #[Test]
    public function isWaitlistRegistrationReturnsFalseIfWaitlistNotEnabled(): void
    {
        $event = new Event();
        $event->setEnableWaitlist(false);

        self::assertFalse($this->subject->isWaitlistRegistration($event, 1));
    }

    #[Test]
    public function isWaitlistRegistrationReturnsTrueIfEventNotFullyBookedAndNotEnoughFreePlaces(): void
    {
        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(2);
        $event->addRegistration(new Registration());

        self::assertTrue($this->subject->isWaitlistRegistration($event, 2));
    }

    #[Test]
    public function isWaitlistRegistrationReturnsTrueIfEventFullyBookedAndNotEnoughFreePlaces(): void
    {
        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(1);
        $event->addRegistration(new Registration());

        self::assertTrue($this->subject->isWaitlistRegistration($event, 1));
    }

    #[Test]
    public function isWaitlistRegistrationReturnsFalseIfWaitlistEnabledButEnoughFreePlaces(): void
    {
        $event = new Event();
        $event->setEnableWaitlist(true);
        $event->setMaxParticipants(3);
        $event->addRegistration(new Registration());

        self::assertFalse($this->subject->isWaitlistRegistration($event, 2));
    }

    #[Test]
    public function checkRegistrationAccessThrowsExceptionIfNoUserLoggedIn(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_access.csv');
        $this->expectExceptionCode(1671627320);

        /** @var Registration $existingRegistration */
        $registration = $this->registrationRepository->findByUid(1);

        $serverRequest = new ServerRequest();
        $this->subject->checkRegistrationAccess($serverRequest, $registration);
    }

    #[Test]
    public function checkRegistrationAccessThrowsExceptionIfRegistrationHasNoFeUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_access.csv');
        $this->expectExceptionCode(1671627320);

        $user = new FrontendUserAuthentication();
        $user->user['uid'] = 1;

        $this->get(Context::class)->setAspect(
            'frontend.user',
            GeneralUtility::makeInstance(UserAspect::class, $user)
        );

        /** @var Registration $existingRegistration */
        $registration = $this->registrationRepository->findByUid(1);

        $serverRequest = new ServerRequest();
        $this->subject->checkRegistrationAccess($serverRequest, $registration);
    }

    #[Test]
    public function checkRegistrationAccessThrowsExceptionIfFeUserNotEqualToRegistrationFeUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_access.csv');
        $this->expectExceptionCode(1671627320);

        $user = new FrontendUserAuthentication();
        $user->user['uid'] = 1;

        $this->get(Context::class)->setAspect(
            'frontend.user',
            GeneralUtility::makeInstance(UserAspect::class, $user)
        );

        /** @var Registration $existingRegistration */
        $registration = $this->registrationRepository->findByUid(3);

        $serverRequest = new ServerRequest();
        $this->subject->checkRegistrationAccess($serverRequest, $registration);
    }

    #[Test]
    public function checkRegistrationAccessThrowsNoExceptionIfFeUserEqualToRegistrationFeUser(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/check_registration_access.csv');

        $user = new FrontendUserAuthentication();
        $user->user['uid'] = 1;

        $this->get(Context::class)->setAspect(
            'frontend.user',
            GeneralUtility::makeInstance(UserAspect::class, $user)
        );

        /** @var Registration $existingRegistration */
        $registration = $this->registrationRepository->findByUid(2);

        $serverRequest = new ServerRequest();
        $this->subject->checkRegistrationAccess($serverRequest, $registration);

        self::assertTrue(true);
    }
}
