<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\EmailService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\EmailService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EmailServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\EmailService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = GeneralUtility::makeInstance(EmailService::class);
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Data provider for invalid emails
     *
     * @return array
     */
    public function invalidEmailsDataProvider()
    {
        return [
            'invalidSender' => [
                'invalid',
                'recipient@domain.tld',
            ],
            'invalidRecipient' => [
                'sender@domain.tld',
                'invalid',
            ],
        ];
    }

    /**
     * Test if email-service returns false, if emails are invalid
     *
     * @dataProvider invalidEmailsDataProvider
     * @test
     * @param mixed $sender
     * @param mixed $recipient
     */
    public function sendEmailMessageWithInvalidEmailTest($sender, $recipient)
    {
        $subject = 'A subject';
        $body = 'A body';
        $senderName = 'Sender name';
        $result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
        self::assertFalse($result);
    }
}
