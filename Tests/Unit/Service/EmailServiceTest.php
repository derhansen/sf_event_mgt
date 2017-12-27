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

use DERHANSEN\SfEventMgt\Service\EmailService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(EmailService::class, ['initialize']);
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
     * Test if e-mail-service returns false, if e-mails are invalid
     *
     * @dataProvider invalidEmailsDataProvider
     * @test
     * @return void
     */
    public function sendEmailMessageWithInvalidEmailsTest($sender, $recipient)
    {
        $subject = 'A subject';
        $body = 'A body';
        $senderName = 'Sender name';
        $result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
        $this->assertFalse($result);
    }

    /**
     * Test if e-mail-service sends mails, if data is valid
     *
     * @test
     * @return void
     */
    public function sendEmailMessageWithValidEmailsTest()
    {
        $sender = 'sender@domain.tld';
        $recipient = 'recipient@domain.tld';
        $subject = 'A subject';
        $body = 'A body';
        $senderName = 'Sender name';

        $mailer = $this->getMock(MailMessage::class, [], [], '', false);
        $mailer->expects($this->once())->method('setFrom')->with($this->equalTo($sender), $this->equalTo($senderName));
        $mailer->expects($this->once())->method('setSubject')->with($subject);
        $mailer->expects($this->once())->method('setBody')->with($this->equalTo($body), $this->equalTo('text/html'));
        $mailer->expects($this->once())->method('setTo')->with($recipient);
        $mailer->expects($this->once())->method('send');
        $mailer->expects($this->once())->method('isSent')->will($this->returnValue(true));
        $this->subject->_set('mailer', $mailer);

        $result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
        $this->assertTrue($result);
    }

    /**
     * Test if e-mail-service adds attachment
     *
     * @test
     * @return void
     */
    public function sendEmailMessageWithValidEmailsAddsAttachments()
    {
        $sender = 'sender@domain.tld';
        $recipient = 'recipient@domain.tld';
        $subject = 'A subject';
        $body = 'A body';
        $senderName = 'Sender name';
        $attachments = [
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Tests/Unit/Fixtures/Attachment.txt')
        ];

        $mailer = $this->getMock(MailMessage::class, [], [], '', false);
        $mailer->expects($this->once())->method('setFrom')->with($this->equalTo($sender), $this->equalTo($senderName));
        $mailer->expects($this->once())->method('setSubject')->with($subject);
        $mailer->expects($this->once())->method('setBody')->with($this->equalTo($body), $this->equalTo('text/html'));
        $mailer->expects($this->once())->method('setTo')->with($recipient);
        $mailer->expects($this->once())->method('attach');
        $mailer->expects($this->once())->method('send');
        $mailer->expects($this->once())->method('isSent')->will($this->returnValue(true));
        $this->subject->_set('mailer', $mailer);

        $result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName, $attachments);
        $this->assertTrue($result);
    }
}
