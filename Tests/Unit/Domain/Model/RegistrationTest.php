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

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
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
	public function getFirstnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getFirstname()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getLastnameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getLastname()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setTitleForStringSetsTitle() {
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getCompanyReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCompany()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setCompanyForStringSetsCompany() {
		$this->subject->setCompany('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'company',
			$this->subject
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getAddressReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getAddress()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getZipReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getZip()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getCityReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCity()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getCountryReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCountry()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function setCountryForStringSetsCountry() {
		$this->subject->setCountry('A country');

		$this->assertAttributeEquals(
			'A country',
			'country',
			$this->subject
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getPhoneReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getPhone()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getEmailReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getEmail()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getGenderReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getGender()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getConfirmedReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getConfirmed()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function isConfirmedReturnsConfirmedState() {
		$this->subject->setConfirmed(TRUE);
		$this->assertTrue($this->subject->isConfirmed());
	}

	/**
	 * @test
	 * @return void
	 */
	public function getPaidReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getPaid()
		);
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function isPaidReturnsPaidState() {
		$this->subject->setPaid(TRUE);
		$this->assertTrue($this->subject->isPaid());
	}

	/**
	 * @test
	 * @return void
	 */
	public function getNotesReturnsInitialValueForString() {
		$this->assertSame('', $this->subject->getNotes());
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function setEventForEventSetsEvent() {
		$event = new \DERHANSEN\SfEventMgt\Domain\Model\Event();
		$this->subject->setEvent($event);
		$this->assertEquals($event, $this->subject->getEvent());
	}

	/**
	 * @test
	 * @return void
	 */
	public function getMainRegistrationReturnsInitialValue() {
		$this->assertEquals(NULL, $this->subject->getMainRegistration());
	}

	/**
	 * @test
	 * @return void
	 */
	public function setMainRegistrationForRegistrationSetsRegistration() {
		$registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
		$this->subject->setMainRegistration($registration);
		$this->assertEquals($registration, $this->subject->getMainRegistration());
	}

	/**
	 * @test
	 * @return void
	 */
	public function getConfirmationUntilReturnsInitialValueForDateTime() {
		$this->assertSame(NULL, $this->subject->getConfirmationUntil());
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getDateOfBirthReturnsInitialValueForDateTime() {
		$this->assertNull($this->subject->getDateOfBirth());
	}

	/**
	 * @test
	 * @return void
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
	 * @return void
	 */
	public function getHiddenReturnsInitialValueForBoolean() {
		$this->assertSame(
			FALSE,
			$this->subject->getHidden()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setHiddenSetsHiddenFlag() {
		$this->subject->setHidden(TRUE);
		$this->assertTrue($this->subject->getHidden());
	}

	/**
	 * @test
	 * @return void
	 */
	public function amountOfRegistrationReturnsInitialValue() {
		$this->assertEquals(1, $this->subject->getAmountOfRegistrations());
	}

	/**
	 * @test
	 * @return void
	 */
	public function amountOfRegistrationSetsAmountOfRegistrations() {
		$this->subject->setAmountOfRegistrations(2);
		$this->assertEquals(2, $this->subject->getAmountOfRegistrations());
	}
}
