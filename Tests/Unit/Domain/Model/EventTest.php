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

use SKYFILLERS\SfEventMgt\Domain\Model\Registration;

/**
 * Test case for class \SKYFILLERS\SfEventMgt\Domain\Model\Event.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \SKYFILLERS\SfEventMgt\Domain\Model\Event
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \SKYFILLERS\SfEventMgt\Domain\Model\Event();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
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
	 */
	public function getDescriptionReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getDescription()
		);
	}

	/**
	 * @test
	 */
	public function setDescriptionForStringSetsDescription() {
		$this->subject->setDescription('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'description',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getStartdateReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getStartdate()
		);
	}

	/**
	 * @test
	 */
	public function setStartdateForDateTimeSetsStartdate() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setStartdate($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'startdate',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getEnddateReturnsInitialValueForDateTime() {
		$this->assertEquals(
			NULL,
			$this->subject->getEnddate()
		);
	}

	/**
	 * @test
	 */
	public function setEnddateForDateTimeSetsEnddate() {
		$dateTimeFixture = new \DateTime();
		$this->subject->setEnddate($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'enddate',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getParticipantsReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getMaxParticipants()
		);
	}

	/**
	 * @test
	 */
	public function setParticipantsForIntegerSetsParticipants() {
		$this->subject->setMaxParticipants(12);

		$this->assertAttributeEquals(
			12,
			'maxParticipants',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getPriceReturnsInitialValueForFloat() {
		$this->assertSame(
			0.0,
			$this->subject->getPrice()
		);
	}

	/**
	 * @test
	 */
	public function setPriceForFloatSetsPrice() {
		$this->subject->setPrice(3.14159265);

		$this->assertAttributeEquals(
			3.14159265,
			'price',
			$this->subject,
			'',
			0.000000001
		);
	}

	/**
	 * @test
	 */
	public function getCurrencyReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getCurrency()
		);
	}

	/**
	 * @test
	 */
	public function setCurrencyForStringSetsCurrency() {
		$this->subject->setCurrency('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'currency',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCategoryReturnsInitialValueForCategory() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getCategory()
		);
	}

	/**
	 * @test
	 */
	public function setCategoryForObjectStorageContainingCategorySetsCategory() {
		$category = new \SKYFILLERS\SfEventMgt\Domain\Model\Category();
		$objectStorageHoldingExactlyOneCategory = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneCategory->attach($category);
		$this->subject->setCategory($objectStorageHoldingExactlyOneCategory);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneCategory,
			'category',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addCategoryToObjectStorageHoldingCategory() {
		$category = new \SKYFILLERS\SfEventMgt\Domain\Model\Category();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->addCategory($category);
	}

	/**
	 * @test
	 */
	public function removeCategoryFromObjectStorageHoldingCategory() {
		$category = new \SKYFILLERS\SfEventMgt\Domain\Model\Category();
		$categoryObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$categoryObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($category));
		$this->inject($this->subject, 'category', $categoryObjectStorageMock);

		$this->subject->removeCategory($category);

	}

	/**
	 * @test
	 */
	public function getRegistrationReturnsInitialValueForRegistration() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getRegistration()
		);
	}

	/**
	 * @test
	 */
	public function setRegistrationForObjectStorageContainingRegistrationSetsRegistration() {
		$registration = new Registration();
		$objectStorageHoldingExactlyOneRegistration = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneRegistration->attach($registration);
		$this->subject->setRegistration($objectStorageHoldingExactlyOneRegistration);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneRegistration,
			'registration',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addRegistrationToObjectStorageHoldingRegistration() {
		$booking = new Registration();
		$bookingObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$bookingObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($booking));
		$this->inject($this->subject, 'registration', $bookingObjectStorageMock);

		$this->subject->addRegistration($booking);
	}

	/**
	 * @test
	 */
	public function removeRegistrationFromObjectStorageHoldingRegistration() {
		$booking = new Registration();
		$bookingObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$bookingObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($booking));
		$this->inject($this->subject, 'registration', $bookingObjectStorageMock);

		$this->subject->removeRegistration($booking);
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfEventHasTakenPlace() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('yesterday'));
		$this->subject->setStartdate($startdate);

		$this->assertFalse($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsFalseIfEventMaxParticipantsReached() {
		$registration = new Registration();
		$registration->setFirstname('John');
		$registration->setLastname('Doe');

		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(1);
		$this->subject->addRegistration($registration);

		$this->assertFalse($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsTrueIfMaxParticipantsNotSet() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

	/**
	 * @test
	 */
	public function getRegistrationPossibleReturnsTrueIfRegistrationIsLogicallyPossible() {
		$startdate = new \DateTime();
		$startdate->add(\DateInterval::createFromDateString('tomorrow'));
		$this->subject->setStartdate($startdate);
		$this->subject->setMaxParticipants(1);

		$this->assertTrue($this->subject->getRegistrationPossible());
	}

}
