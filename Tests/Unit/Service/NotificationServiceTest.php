<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\EmailService;
use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\NotificationService.
 */
class NotificationServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\NotificationService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new NotificationService();

        $GLOBALS['BE_USER'] = new \stdClass();
        $GLOBALS['BE_USER']->uc['lang'] = '';
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Data provider for messageType
     *
     * @return array
     */
    public function messageTypeDataProvider()
    {
        return [
            'messageTypeRegistrationNew' => [
                MessageType::REGISTRATION_NEW
            ],
            'messageTypeRegistrationWaitlistNew' => [
                MessageType::REGISTRATION_WAITLIST_NEW
            ],
            'messageTypeRegistrationConfirmed' => [
                MessageType::REGISTRATION_CONFIRMED
            ],
            'messageTypeRegistrationWaitlistConfirmed' => [
                MessageType::REGISTRATION_WAITLIST_CONFIRMED
            ]
        ];
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendUserMessageReturnsFalseIfIgnoreNotificationsSet($messageType)
    {
        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('valid@email.tld');
        $registration->setIgnoreNotifications(true);

        $settings = ['notification' => ['senderEmail' => 'valid@email.tld']];

        $result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function sendUserMessageReturnsFalseIfInvalidArgumentsGiven()
    {
        $result = $this->subject->sendUserMessage(null, null, null, null);
        self::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendUserMessageReturnsFalseIfSendFailed($messageType)
    {
        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('valid@email.tld');

        $settings = ['notification' => ['senderEmail' => 'valid@email.tld']];

        $emailService = $this->getMockBuilder(EmailService::class)->setMethods(['sendEmailMessage'])->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(false);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->setMethods(['getAttachments'])->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::atLeast(2))->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
        self::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendUserMessageReturnsTrueIfSendSuccessful($messageType)
    {
        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('valid@email.tld');

        $settings = ['notification' => ['senderEmail' => 'valid@email.tld']];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(true);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::atLeast(2))->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
        self::assertTrue($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendAdminNewRegistrationMessageReturnsFalseIfSendFailed($messageType)
    {
        $event = new Event();
        $registration = new Registration();

        $settings = [
            'notification' => [
                'senderEmail' => 'valid@email.tld',
                'senderName' => 'Sender',
                'adminEmail' => 'valid@email.tld'
            ]
        ];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(false);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function sendAdminMessageReturnsFalseIfInvalidArgumentsGiven()
    {
        $result = $this->subject->sendAdminMessage(null, null, null, MessageType::REGISTRATION_NEW);
        self::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendAdminNewRegistrationMessageReturnsTrueIfSendSuccessful($messageType)
    {
        $event = new Event();
        $registration = new Registration();

        $settings = [
            'notification' => [
                'senderEmail' => 'valid@email.tld',
                'senderName' => 'Sender',
                'adminEmail' => 'valid@email.tld'
            ]
        ];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(true);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
        self::assertTrue($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendAdminMessageDoesNotSendEmailIfNotifyAdminAndNotifyOrganiserIsFalse($messageType)
    {
        $event = new Event();
        $event->setNotifyAdmin(false);
        $event->setNotifyOrganisator(false);
        $registration = new Registration();

        $settings = [
            'notification' => [
                'senderEmail' => 'valid@email.tld',
                'adminEmail' => 'valid@email.tld'
            ]
        ];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::never())->method('sendEmailMessage');
        $this->inject($this->subject, 'emailService', $emailService);

        $result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
        self::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendAdminMessageSendsEmailToOrganisatorIfConfigured($messageType)
    {
        $organisator = new Organisator();
        $event = new Event();
        $event->setNotifyAdmin(false);
        $event->setNotifyOrganisator(true);
        $event->setOrganisator($organisator);
        $registration = new Registration();

        $settings = [
            'notification' => [
                'senderEmail' => 'valid@email.tld',
                'senderName' => 'Sender',
                'adminEmail' => 'valid@email.tld'
            ]
        ];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(true);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function sendAdminMessageUsesRegistrationDataAsSenderIfConfigured()
    {
        $organisator = new Organisator();
        $event = new Event();
        $event->setNotifyAdmin(false);
        $event->setNotifyOrganisator(true);
        $event->setOrganisator($organisator);

        $settings = [
            'notification' => [
                'registrationDataAsSenderForAdminEmails' => 1,
            ]
        ];

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getFullname')->willReturn('Sender');
        $mockRegistration->expects(self::once())->method('getEmail')->willReturn('email@domain.tld');

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::once())->method('sendEmailMessage')->willReturn(true);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendAdminMessage($event, $mockRegistration, $settings, MessageRecipient::ADMIN);
        self::assertTrue($result);
    }

    /**
     * Test if the adminEmail settings get exploded and only 2 emails get sent
     *
     * @test
     * @dataProvider messageTypeDataProvider
     * @param mixed $messageType
     */
    public function sendMultipleAdminNewRegistrationMessageReturnsTrueIfSendSuccessful($messageType)
    {
        $event = new Event();
        $registration = new Registration();

        $settings = [
            'notification' => [
                'senderEmail' => 'valid@email.tld',
                'senderName' => 'Sender',
                'adminEmail' => 'valid1@email.tld,valid2@email.tld ,invalid-email,,'
            ]
        ];

        $emailService = $this->getMockBuilder(EmailService::class)->getMock();
        $emailService->expects(self::exactly(3))->method('sendEmailMessage')->willReturn(true);
        $this->inject($this->subject, 'emailService', $emailService);

        $attachmentService = $this->getMockBuilder(AttachmentService::class)->getMock();
        $attachmentService->expects(self::once())->method('getAttachments')->willReturn([]);
        $this->inject($this->subject, 'attachmentService', $attachmentService);

        $hashService = $this->getMockBuilder(HashService::class)->getMock();
        $hashService->expects(self::once())->method('generateHmac')->willReturn('HMAC');
        $hashService->expects(self::once())->method('appendHmac')->willReturn('HMAC');
        $this->inject($this->subject, 'hashService', $hashService);

        $fluidStandaloneService = $this->getMockBuilder(FluidStandaloneService::class)
            ->setMethods(['renderTemplate', 'parseStringFluid'])
            ->disableOriginalConstructor()
            ->getMock();
        $fluidStandaloneService->expects(self::once())->method('renderTemplate')->willReturn('');
        $fluidStandaloneService->expects(self::once())->method('parseStringFluid')->willReturn('');
        $this->inject($this->subject, 'fluidStandaloneService', $fluidStandaloneService);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch');
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function sendUserMessageReturnsFalseIfNoCustomMessageGiven()
    {
        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('valid@email.tld');

        $settings = ['notification' => ['senderEmail' => 'valid@email.tld']];

        $result = $this->subject->sendUserMessage(
            $event,
            $registration,
            $settings,
            MessageType::CUSTOM_NOTIFICATION,
            null
        );
        self::assertFalse($result);
    }

    /**
     * Test that only confirmed registrations get notified. Also test, if the ignoreNotifications
     * flag is evaluated
     *
     * @test
     */
    public function sendCustomNotificationReturnsExpectedAmountOfNotificationsSent()
    {
        $event = new Event();

        $registration1 = new Registration();
        $registration1->setConfirmed(true);
        $registration2 = new Registration();
        $registration2->setConfirmed(true);

        /** @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registrations */
        $registrations = new ObjectStorage();
        $registrations->attach($registration1);
        $registrations->attach($registration2);

        /** @var NotificationService $mockNotificationService */
        $mockNotificationService = $this->getMockBuilder(NotificationService::class)
            ->setMethods(['sendUserMessage'])
            ->getMock();
        $mockNotificationService->expects(self::any())->method('sendUserMessage')->willReturn(true);

        $registrationRepository = $this->getMockBuilder(RegistrationRepository::class)
            ->setMethods(['findNotificationRegistrations'])
            ->disableOriginalConstructor()
            ->getMock();
        $registrationRepository->expects(self::once())->method('findNotificationRegistrations')->willReturn(
            $registrations
        );
        $this->inject($mockNotificationService, 'registrationRepository', $registrationRepository);

        $customNotification = new CustomNotification();
        $customNotification->setTemplate('aTemplate');

        $result = $mockNotificationService->sendCustomNotification($event, $customNotification, ['someSettings']);
        self::assertEquals(2, $result);
    }

    /**
     * @test
     */
    public function createCustomNotificationLogentryCreatesLog()
    {
        $mockLogRepo = $this->getMockBuilder(CustomNotificationLogRepository::class)
            ->setMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockLogRepo->expects(self::once())->method('add');
        $this->inject($this->subject, 'customNotificationLogRepository', $mockLogRepo);

        $event = new Event();
        $event->setPid(1);
        $this->subject->createCustomNotificationLogentry($event, 'A description', 1);
    }

    /**
     * @test
     */
    public function userNotificationNotSentIfNotificationsDisabled()
    {
        $mockEvent = $this->prophesize(Event::class);
        $mockRegistration = $this->prophesize(Registration::class);
        $settings = [
            'notification' => [
                'disabled' => 1
            ]
        ];
        $result = $this->subject->sendUserMessage(
            $mockEvent,
            $mockRegistration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function adminNotificationNotSentIfNotificationsDisabled()
    {
        $mockEvent = $this->prophesize(Event::class);
        $mockRegistration = $this->prophesize(Registration::class);
        $settings = [
            'notification' => [
                'disabled' => 1
            ]
        ];
        $result = $this->subject->sendAdminMessage(
            $mockEvent,
            $mockRegistration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertFalse($result);
    }
}
