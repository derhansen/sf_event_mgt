<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\FrontendUserRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
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
}
