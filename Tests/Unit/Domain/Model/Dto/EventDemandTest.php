<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

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
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventDemandTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand();
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
	 */
	public function getDisplayModeReturnsInitialValue() {
		$this->assertSame(
			'all',
			$this->subject->getDisplayMode()
		);
	}

	/**
	 * @test
	 */
	public function getTopEventRestrictionReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getTopEventRestriction()
		);
	}

	/**
	 * @test
	 */
	public function setTopEventRestrictionForIntegerSetsTopEventRestriction() {
		$this->subject->setTopEventRestriction(1);
		$this->assertSame(
			1,
			$this->subject->getTopEventRestriction()
		);
	}

	/**
	 * @test
	 */
	public function setDisplayModeForStringSetsDisplayMode() {
		$this->subject->setDisplayMode('past');

		$this->assertAttributeEquals(
			'past',
			'displayMode',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getStoragePageReturnsInitialValue() {
		$this->assertSame(
			NULL,
			$this->subject->getStoragePage()
		);
	}

	/**
	 * @test
	 */
	public function setStoragePageForStringSetsStoragePage() {
		$this->subject->setStoragePage('1,2,3');

		$this->assertAttributeEquals(
			'1,2,3',
			'storagePage',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCurrentDateTimeReturnsCurrentDateTimeIfNoValueSet() {
		$this->assertEquals(
			new \DateTime,
			$this->subject->getCurrentDateTime()
		);
	}

	/**
	 * @test
	 */
	public function getCurrentDateTimeReturnsGivenValueIfValueSet() {
		$this->subject->setCurrentDateTime(new \DateTime('01.01.2014'));
		$this->assertEquals(
			new \DateTime('01.01.2014'),
			$this->subject->getCurrentDateTime()
		);
	}

	/**
	 * @test
	 */
	public function getCategoryForStringSetsCategory() {
		$this->subject->setCategory('1,2,3,4');
		$this->assertEquals(
			'1,2,3,4',
			$this->subject->getCategory()
		);
	}

	/**
	 * @test
	 */
	public function getStartDateReturnsNullIfNoValueSet() {
		$this->assertSame(
			NULL,
			$this->subject->getStartDate()
		);
	}

	/**
	 * @test
	 */
	public function getStartDateReturnsGivenValueIfValueSet() {
		$this->subject->setStartDate(new \DateTime('01.01.2014 10:00:00'));
		$this->assertEquals(
			new \DateTime('01.01.2014 10:00:00'),
			$this->subject->getStartDate()
		);
	}

	/**
	 * @test
	 */
	public function getEndDateReturnsNullIfNoValueSet() {
		$this->assertSame(
			NULL,
			$this->subject->getEndDate()
		);
	}

	/**
	 * @test
	 */
	public function getEndDateReturnsGivenValueIfValueSet() {
		$this->subject->setEndDate(new \DateTime('01.01.2014 10:00:00'));
		$this->assertEquals(
			new \DateTime('01.01.2014 10:00:00'),
			$this->subject->getEndDate()
		);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsEmptyStringIfNoValueSet() {
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsGivenValueIfValueSet() {
		$this->subject->setTitle('test title');
		$this->assertEquals(
			'test title',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderFieldReturnsEmptyStringIfNoValueSet() {
		$this->assertSame(
			'',
			$this->subject->getOrderField()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderFieldReturnsGivenValueIfValueSet() {
		$this->subject->setOrderField('title');
		$this->assertSame(
			'title',
			$this->subject->getOrderField()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderDirectionReturnsEmptyStringIfNoValueSet() {
		$this->assertSame(
			'',
			$this->subject->getOrderField()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getOrderDirectionReturnsGivenValueIfValueSet() {
		$this->subject->setOrderField('asc');
		$this->assertSame(
			'asc',
			$this->subject->getOrderField()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getQueryLimitReturnsNullIfNoValueSet() {
		$this->assertNull($this->subject->getQueryLimit());
	}

	/**
	 * @test
	 * @return void
	 */
	public function getQueryLimitReturnsExpectedQueryLimit() {
		$this->subject->setQueryLimit(10);
		$this->assertSame(
			10,
			$this->subject->getQueryLimit()
		);
	}

}
