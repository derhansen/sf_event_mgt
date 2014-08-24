<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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
use DERHANSEN\SfEventMgt\Domain\Model\Event;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog
 */
class CustomNotificationLogTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog();
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
	 * Test if initial value for event is returned
	 *
	 * @test
	 * @return void
	 */
	public function getEventReturnsInitialValueForEvent() {
		$this->assertSame(
			NULL,
			$this->subject->getEvent()
		);
	}

	/**
	 * Test if event can be set
	 *
	 * @test
	 * @return void
	 */
	public function setEventForEventSetsEvent() {
		$event = new Event();
		$this->subject->setEvent($event);
		$this->assertEquals($event, $this->subject->getEvent());
	}

	/**
	 * Test if details can be set
	 *
	 * @test
	 * @return void
	 */
	public function setDetailsForStringSetsDetails() {
		$this->subject->setDetails('Description');
		$this->assertEquals('Description', $this->subject->getDetails());
	}

	/**
	 * Test if emailsSent can be set
	 *
	 * @test
	 * @return void
	 */
	public function setEmailsSentForIntSetsEmailsSent() {
		$this->subject->setEmailsSent(100);
		$this->assertEquals(100, $this->subject->getEmailsSent());
	}

	/**
	 * Test if tstamp can be set
	 *
	 * @test
	 * @return void
	 */
	public function setTstampForDateTimeSetsTstamp() {
		$tstamp = new \DateTime('01.01.2014 10:00:00');
		$this->subject->setTstamp($tstamp);
		$this->assertEquals($tstamp, $this->subject->getTstamp());
	}

}
