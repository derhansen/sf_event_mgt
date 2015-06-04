<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/**
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
	 * @test
	 * @return void
	 */
	public function sendEmailMessageTest() {
		$sender = 'name@domain.tld';
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
		$this->subject->_set('mailer', $mailer);

		$this->subject->sendEmailMessage($sender, $recipient, $subject, $body, $senderName);
	}

}
