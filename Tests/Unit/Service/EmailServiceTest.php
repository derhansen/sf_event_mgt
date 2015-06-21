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

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\EmailService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EmailServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \DERHANSEN\SfEventMgt\Service\EmailService
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\Service\\EmailService', array('dummy'));
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * Data provider for invalid emails
	 *
	 * @return array
	 */
	public function invalidEmailsDataProvider() {
		return array(
			'invalidSender' => array(
				'invalid',
				'recipient@domain.tld',
			),
			'invalidRecipient' => array(
				'sender@domain.tld',
				'invalid',
			),
		);
	}

	/**
	 * Test if e-mail-service returns false, if e-mails are invalid
	 *
	 * @dataProvider invalidEmailsDataProvider
	 * @test
	 * @return void
	 */
	public function sendEmailMessageWithInvalidEmailsTest($sender, $recipient) {
		$subject = 'A subject';
		$body = 'A body';
		$senderName = 'Sender name';
		$result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
		$this->assertFalse($result);
	}

	/**
	 * Test if e-mail-service sends mails, if e-mails are valid
	 *
	 * @test
	 * @return void
	 */
	public function sendEmailMessageWithValidEmailsTest() {
		$sender = 'sender@domain.tld';
		$recipient = 'recipient@domain.tld';
		$subject = 'A subject';
		$body = 'A body';
		$senderName = 'Sender name';

		$mailer = $this->getMock('TYPO3\\CMS\\Core\\Mail\\MailMessage', array(), array(), '', FALSE);
		$mailer->expects($this->once())->method('setFrom')->with($this->equalTo($sender), $this->equalTo($senderName));
		$mailer->expects($this->once())->method('setSubject')->with($subject);
		$mailer->expects($this->once())->method('setBody')->with($this->equalTo($body), $this->equalTo('text/html'));
		$mailer->expects($this->once())->method('setTo')->with($recipient);
		$mailer->expects($this->once())->method('send');
		$mailer->expects($this->once())->method('isSent')->will($this->returnValue(TRUE));
		$this->subject->_set('mailer', $mailer);

		$result = $this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
		$this->assertTrue($result);
	}
}
