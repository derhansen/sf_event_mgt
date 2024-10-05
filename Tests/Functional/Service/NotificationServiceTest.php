<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Repository\CustomNotificationLogRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Service\EmailService;
use DERHANSEN\SfEventMgt\Service\FluidRenderingService;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Service\NotificationService;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class NotificationServiceTest extends FunctionalTestCase
{
    protected NotificationService $subject;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected function setUp(): void
    {
        parent::setUp();

        $registrationRepository = $this->get(RegistrationRepository::class);
        $emailService = $this->get(EmailService::class);
        $hashService = $this->get(HashService::class);
        $fluidRenderingService = $this->get(FluidRenderingService::class);
        $customNotificationLogRepository = $this->get(CustomNotificationLogRepository::class);
        $attachmentService = $this->get(AttachmentService::class);
        $eventDispatcher = $this->get(EventDispatcherInterface::class);
        $context = $this->get(Context::class);

        $this->subject = new NotificationService(
            $registrationRepository,
            $emailService,
            $hashService,
            $fluidRenderingService,
            $customNotificationLogRepository,
            $attachmentService,
            $eventDispatcher,
            $context
        );
    }

    #[Test]
    public function sendUserMessageDoesNotSendMessageIfNotificationsDisabled(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEvent($event);

        $settings = [
            'notification' => [
                'disabled' => 1,
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendUserMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertFalse($result);
    }

    #[Test]
    public function sendUserMessageDoesNotSendMessageIfIgnoreNotificationsFlagIsSet(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('recipient@registration.tld');
        $registration->setIgnoreNotifications(true);
        $registration->setEvent($event);

        $settings = [
            'notification' => [
                'disabled' => 0,
                'senderEmail' => 'admin@events.tld',
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendUserMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertFalse($result);
    }

    #[Test]
    public function sendUserMessageDoesNotSendMessageIfcustomNotificationCanNotBeResolved(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('recipient@registration.tld');
        $registration->setIgnoreNotifications(true);
        $registration->setEvent($event);

        $customNotification = new CustomNotification();
        $customNotification->setTemplate('EXT:foo/bar.html');

        $settings = [
            'notification' => [
                'disabled' => 0,
                'senderEmail' => 'admin@events.tld',
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendUserMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::CUSTOM_NOTIFICATION,
            $customNotification
        );
        self::assertFalse($result);
    }

    #[Test]
    public function sendUserMessageSendsMessageSuccessfully(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('recipient@registration.tld');
        $registration->setEvent($event);

        $settings = [
            'notification' => [
                'disabled' => 0,
                'senderEmail' => 'admin@events.tld',
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendUserMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertTrue($result);
    }

    #[Test]
    public function sendAdminMessageDoesNotSendMessageIfNotificationsDisabled(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEvent($event);

        $settings = [
            'notification' => [
                'disabled' => 1,
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendAdminMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertFalse($result);
    }

    #[Test]
    public function sendAdminMessageSendsMessageSuccessfully(): void
    {
        $extbaseRequest = $this->getExtbaseRequest();

        $event = new Event();
        $registration = new Registration();
        $registration->setEmail('recipient@registration.tld');
        $registration->setEvent($event);

        $settings = [
            'notification' => [
                'disabled' => 0,
                'senderEmail' => 'admin@events.tld',
                'registrationNew' => [
                    'userSubject' => 'My subject',
                ],
            ],
        ];

        $result = $this->subject->sendAdminMessage(
            $extbaseRequest,
            $event,
            $registration,
            $settings,
            MessageType::REGISTRATION_NEW
        );
        self::assertTrue($result);
    }

    #[Test]
    public function sendCustomNotificationReturnsZeroIfNoTemplateDefined(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/send_custom_notifications.csv');

        $extbaseRequest = $this->getExtbaseRequest();

        $eventRepository = $this->get(EventRepository::class);
        $event = $eventRepository->findByUid(50);

        $customNotification = new CustomNotification();

        $result = $this->subject->sendCustomNotification(
            $extbaseRequest,
            $event,
            $customNotification,
            []
        );
        self::assertEquals(0, $result);
    }

    #[Test]
    public function sendCustomNotificationReturnsAmountOfSentNotificationForConfirmedConstraint(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/send_custom_notifications.csv');

        $extbaseRequest = $this->getExtbaseRequest();

        $eventRepository = $this->get(EventRepository::class);
        $event = $eventRepository->findByUid(50);

        $customNotification = new CustomNotification();
        $customNotification->setTemplate('thanksForParticipation');
        $customNotification->setRecipients(CustomNotification::RECIPIENTS_CONFIRMED);

        $settings = [
            'notification' => [
                'senderEmail' => 'admin@events.tld',
                'customNotifications' => [
                    'thanksForParticipation' => [
                        'title' => 'Thank you message,',
                        'template' => 'ThanksForParticipation.html',
                        'subject' => 'Thank you for participation in event "{event.title}"',
                    ],
                ],
            ],
        ];

        $result = $this->subject->sendCustomNotification(
            $extbaseRequest,
            $event,
            $customNotification,
            $settings
        );
        self::assertEquals(2, $result);
    }

    #[Test]
    public function sendCustomNotificationReturnsAmountOfSentNotificationForUnconfirmedConstraint(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/send_custom_notifications.csv');

        $extbaseRequest = $this->getExtbaseRequest();

        $eventRepository = $this->get(EventRepository::class);
        $event = $eventRepository->findByUid(50);

        $customNotification = new CustomNotification();
        $customNotification->setTemplate('thanksForParticipation');
        $customNotification->setRecipients(CustomNotification::RECIPIENTS_UNCONFIRMED);

        $settings = [
            'notification' => [
                'senderEmail' => 'admin@events.tld',
                'customNotifications' => [
                    'thanksForParticipation' => [
                        'title' => 'Thank you message,',
                        'template' => 'ThanksForParticipation.html',
                        'subject' => 'Thank you for participation in event "{event.title}"',
                    ],
                ],
            ],
        ];

        $result = $this->subject->sendCustomNotification(
            $extbaseRequest,
            $event,
            $customNotification,
            $settings
        );
        self::assertEquals(1, $result);
    }

    protected function getExtbaseRequest(): RequestInterface
    {
        $contentObjectRendererMock = $this->getMockBuilder(ContentObjectRenderer::class)->disableOriginalConstructor()->getMock();
        $contentObjectRendererMock->expects(self::any())->method('parseFunc')->willReturn('Foo');
        GeneralUtility::addInstance(ContentObjectRenderer::class, $contentObjectRendererMock);

        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('currentContentObject', $contentObjectRendererMock);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        return new Request($serverRequest);
    }
}
