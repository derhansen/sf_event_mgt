<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service\Notification;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\ICalendarService;
use DERHANSEN\SfEventMgt\Service\Notification\AttachmentService;
use DERHANSEN\SfEventMgt\Utility\MessageRecipient;
use DERHANSEN\SfEventMgt\Utility\MessageType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\Notification\AttachmentService.
 */
class AttachmentServiceTest extends UnitTestCase
{
    protected AttachmentService $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $mockICalendarService = $this->getMockBuilder(ICalendarService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new AttachmentService($mockICalendarService);
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    public static function typoScriptConfigTestDataProvider(): array
    {
        return [
            'noTyposcriptSettingsForMessageType' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::USER,
                'invalidTypoScriptSetting',
                [],
            ],
            'messageTypeRegistrationNewForUser' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::USER,
                'registrationNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf'),
                ],
            ],
            'messageTypeRegistrationWaitlistNewForUser' => [
                MessageType::REGISTRATION_WAITLIST_NEW,
                MessageRecipient::USER,
                'registrationWaitlistNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf'),
                ],
            ],
            'messageTypeRegistrationConfirmedForUser' => [
                MessageType::REGISTRATION_CONFIRMED,
                MessageRecipient::USER,
                'registrationConfirmed',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf'),
                ],
            ],
            'messageTypeRegistrationWaitlistConfirmedForUser' => [
                MessageType::REGISTRATION_WAITLIST_CONFIRMED,
                MessageRecipient::USER,
                'registrationWaitlistConfirmed',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf'),
                ],
            ],
            'messageTypeRegistrationNewForAdmin' => [
                MessageType::REGISTRATION_NEW,
                MessageRecipient::ADMIN,
                'registrationNew',
                [
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment1.pdf'),
                    GeneralUtility::getFileAbsFileName('fileadmin/attachment2.pdf'),
                ],
            ],
        ];
    }

    /**
     * Tests if TypoScript settings are respected for the configured MessageTypes
     * and expected fromFiles result are returned
     *
     * @param mixed $messageType
     * @param mixed $messageRecipient
     * @param mixed $settingsPath
     * @param mixed $expected
     */
    #[DataProvider('typoScriptConfigTestDataProvider')]
    #[Test]
    public function getAttachmentsRespectsTypoScriptSettingsForGivenMessageType(
        $messageType,
        $messageRecipient,
        $settingsPath,
        $expected
    ) {
        $event = new Event();
        $registration = new Registration();
        $registration->setEvent($event);

        $settings = ['notification' => [
            $settingsPath => [
                'attachments' => [
                    $messageRecipient => [
                        'fromFiles' => [
                            'fileadmin/attachment1.pdf',
                            'fileadmin/attachment2.pdf',
                        ],
                    ],
                ],
            ],
        ]];

        $attachments = $this->subject->getAttachments($settings, $registration, $messageType, $messageRecipient);
        self::assertEquals($expected, $attachments);
    }
}
