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

use SKYFILLERS\SfEventMgt\Utility\MessageType;

/**
 * Test case for class SKYFILLERS\SfEventMgt\Service\NotificationService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class NotificationServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \SKYFILLERS\SfEventMgt\Service\NotificationService
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \SKYFILLERS\SfEventMgt\Service\NotificationService();
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
	 * Data provider for messageType
	 *
	 * @return array
	 */
	public function messageTypeDataProvider() {
		return array(
			'messageTypeMissing' => array(
				NULL
			),
			'messageTypeRegistrationNew' => array(
				MessageType::REGISTRATION_NEW
			),
			'messageTypeRegistrationConfirmed' => array(
				MessageType::REGISTRATION_CONFIRMED
			),
		);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendUserMessageReturnsFalseIfInvalidEmailInRegistration($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setEmail('invalid-email');

		$settings = array('notification' => array('senderEmail' => 'valid@email.tld'));

		$result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendUserMessageReturnsFalseIfInvalidEmailInSettings($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setEmail('valid@email.tld');

		$settings = array('notification' => array('senderEmail' => 'invalid-email'));

		$result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendUserMessageReturnsFalseIfSendFailed($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setEmail('valid@email.tld');

		$settings = array('notification' => array('senderEmail' => 'valid@email.tld'));

		$emailService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\EmailService',
			array('sendEmailMessage'), array(), '', FALSE);
		$emailService->expects($this->once())->method('sendEmailMessage')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'emailService', $emailService);

		// Inject configuration and configurationManager
		$configuration = array(
			'plugin.' => array(
				'tx_sfeventmgt.' => array(
					'view.' => array(
						'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
						'layoutRootPath' => 'EXT:sf_event_mgt/Resources/Private/Layouts/'
					)
				)
			)
		);

		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($configuration));
		$this->inject($this->subject, 'configurationManager', $configurationManager);

		$emailView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', array(), array(), '', FALSE);
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->once())->method('get')->will($this->returnValue($emailView));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\HashService');
		$hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('HMAC'));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendUserMessageReturnsTrueIfSendSuccessful($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
		$registration->setEmail('valid@email.tld');

		$settings = array('notification' => array('senderEmail' => 'valid@email.tld'));

		$emailService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\EmailService',
			array('sendEmailMessage'), array(), '', FALSE);
		$emailService->expects($this->once())->method('sendEmailMessage')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'emailService', $emailService);

		// Inject configuration and configurationManager
		$configuration = array(
			'plugin.' => array(
				'tx_sfeventmgt.' => array(
					'view.' => array(
						'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
						'layoutRootPath' => 'EXT:sf_event_mgt/Resources/Private/Layouts/'
					)
				)
			)
		);

		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($configuration));
		$this->inject($this->subject, 'configurationManager', $configurationManager);

		$emailView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', array(), array(), '', FALSE);
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->once())->method('get')->will($this->returnValue($emailView));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\HashService');
		$hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('HMAC'));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->sendUserMessage($event, $registration, $settings, $messageType);
		$this->assertTrue($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendAdminNewRegistrationMessageReturnsFalseIfInvalidEmailInSettings($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();

		$settings = array('notification' => array('senderEmail' => 'invalid-email', 'adminEmail' => 'invalid-email'));
		$result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendAdminNewRegistrationMessageReturnsFalseIfSendFailed($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();

		$settings = array('notification' => array('senderEmail' => 'valid@email.tld',
			'adminEmail' => 'valid@email.tld'));

		$emailService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\EmailService',
			array('sendEmailMessage'), array(), '', FALSE);
		$emailService->expects($this->once())->method('sendEmailMessage')->will($this->returnValue(FALSE));
		$this->inject($this->subject, 'emailService', $emailService);

		// Inject configuration and configurationManager
		$configuration = array(
			'plugin.' => array(
				'tx_sfeventmgt.' => array(
					'view.' => array(
						'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
						'layoutRootPath' => 'EXT:sf_event_mgt/Resources/Private/Layouts/'
					)
				)
			)
		);

		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($configuration));
		$this->inject($this->subject, 'configurationManager', $configurationManager);

		$emailView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', array(), array(), '', FALSE);
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->once())->method('get')->will($this->returnValue($emailView));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\HashService');
		$hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('HMAC'));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
		$this->assertFalse($result);
	}

	/**
	 * @test
	 * @dataProvider messageTypeDataProvider
	 */
	public function sendAdminNewRegistrationMessageReturnsTrueIfSendSuccessful($messageType) {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$registration = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();

		$settings = array('notification' => array('senderEmail' => 'valid@email.tld',
			'adminEmail' => 'valid@email.tld'));

		$emailService = $this->getMock('SKYFILLERS\\SfEventMgt\\Service\\EmailService',
			array('sendEmailMessage'), array(), '', FALSE);
		$emailService->expects($this->once())->method('sendEmailMessage')->will($this->returnValue(TRUE));
		$this->inject($this->subject, 'emailService', $emailService);

		// Inject configuration and configurationManager
		$configuration = array(
			'plugin.' => array(
				'tx_sfeventmgt.' => array(
					'view.' => array(
						'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
						'layoutRootPath' => 'EXT:sf_event_mgt/Resources/Private/Layouts/'
					)
				)
			)
		);

		$configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
			array('getConfiguration'), array(), '', FALSE);
		$configurationManager->expects($this->once())->method('getConfiguration')->will(
			$this->returnValue($configuration));
		$this->inject($this->subject, 'configurationManager', $configurationManager);

		$emailView = $this->getMock('TYPO3\\CMS\\Fluid\\View\\StandaloneView', array(), array(), '', FALSE);
		$objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
			array(), array(), '', FALSE);
		$objectManager->expects($this->once())->method('get')->will($this->returnValue($emailView));
		$this->inject($this->subject, 'objectManager', $objectManager);

		$hashService = $this->getMock('TYPO3\\CMS\\Extbase\\Security\\Cryptography\HashService');
		$hashService->expects($this->once())->method('generateHmac')->will($this->returnValue('HMAC'));
		$this->inject($this->subject, 'hashService', $hashService);

		$result = $this->subject->sendAdminMessage($event, $registration, $settings, $messageType);
		$this->assertTrue($result);
	}
}
