<?php
namespace SKYFILLERS\SfEventMgt\Tests\Unit\Service;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use SKYFILLERS\SfEventMgt\Command\CleanupCommandController;

/**
 * Test case for class SKYFILLERS\SfEventMgt\Service\EmailService.
 */
class EmailServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \SKYFILLERS\SfEventMgt\Service\EmailService
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock('SKYFILLERS\\SfEventMgt\\Service\\EmailService', array('dummy'));
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
