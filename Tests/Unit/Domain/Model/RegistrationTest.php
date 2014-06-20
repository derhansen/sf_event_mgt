<?php

namespace SKYFILLERS\SfEventMgt\Tests\Unit\Domain\Model;

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

/**
 * Test case for class \SKYFILLERS\SfEventMgt\Domain\Model\Registration.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \SKYFILLERS\SfEventMgt\Domain\Model\Registration
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \SKYFILLERS\SfEventMgt\Domain\Model\Registration();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getFirstnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFirstname()
		);
	}

	/**
	 * @test
	 */
	public function setFirstnameForStringSetsFirstname() {
		$this->subject->setFirstname('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'firstname',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLastnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getLastname()
		);
	}

	/**
	 * @test
	 */
	public function setLastnameForStringSetsLastname() {
		$this->subject->setLastname('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'lastname',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getAddressReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getAddress()
		);
	}

	/**
	 * @test
	 */
	public function setAddressForStringSetsAddress() {
		$this->subject->setAddress('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'address',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getZipReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getZip()
		);
	}

	/**
	 * @test
	 */
	public function setZipForIntegerSetsZip() {
		$this->subject->setZip(12);

		$this->assertAttributeEquals(
			12,
			'zip',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCityReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCity()
		);
	}

	/**
	 * @test
	 */
	public function setCityForStringSetsCity() {
		$this->subject->setCity('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'city',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getPhoneReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getPhone()
		);
	}

	/**
	 * @test
	 */
	public function setPhoneForStringSetsPhone() {
		$this->subject->setPhone('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'phone',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEmailReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getEmail()
		);
	}

	/**
	 * @test
	 */
	public function setEmailForStringSetsEmail() {
		$this->subject->setEmail('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'email',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getGenderReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getGender()
		);
	}

	/**
	 * @test
	 */
	public function setGenderForStringSetsGender() {
		$this->subject->setGender('m');

		$this->assertAttributeEquals(
			'm',
			'gender',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getConfirmedReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getConfirmed()
		);
	}

	/**
	 * @test
	 */
	public function setConfirmedForBooleanSetsConfirmed() {
		$this->subject->setConfirmed(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'confirmed',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getPaidReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getPaid()
		);
	}

	/**
	 * @test
	 */
	public function setPaidForBooleanSetsPaid() {
		$this->subject->setPaid(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'paid',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function setNotesForStringSetsNotes() {
		$this->subject->setNotes('This is a longer text');

		$this->assertAttributeEquals(
			'This is a longer text',
			'notes',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function setEventForEventSetsEvent() {
		$event = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
		$this->subject->setEvent($event);
		$this->assertEquals($event, $this->subject->getEvent());
	}

	/**
	 * @test
	 */
	public function setConfirmationUntilForDateTimeSetsConfirmationUntil() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setConfirmationUntil($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'confirmationUntil',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function setDateOfBirthForDateTimeSetsDateOfBirth() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setDateOfBirth($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'dateOfBirth',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getHiddenReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getHidden()
		);
	}
}
