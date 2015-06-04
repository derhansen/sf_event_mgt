<?php

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model;

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
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Location.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LocationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * Location object
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Domain\Model\Location();
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
	 * Test if initial value for title is returned
	 *
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
	 * Test if title can be set
	 *
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
	 * Test if description returns initial value
	 *
	 * @test
	 * @return void
	 */
	public function getDescriptionReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getDescription()
		);
	}

	/**
	 * Test if description can be set
	 *
	 * @test
	 * @return void
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
	 * Test if initial value is returned
	 *
	 * @test
	 * @return void
	 */
	public function getLongitudeReturnsInitialValueForFloat() {
		$this->assertSame(0.0, $this->subject->getLongitude());
	}

	/**
	 * Test if longitude can be set
	 *
	 * @test
	 * @return void
	 */
	public function setLongitudeSetsLongitude() {
		$this->subject->setLongitude(12.345678);
		$this->assertSame(12.345678, $this->subject->getLongitude());
	}

	/**
	 * Test if initial value is returned
	 *
	 * @test
	 * @return void
	 */
	public function getLatitudeReturnsInitialValueForFloat() {
		$this->assertSame(0.0, $this->subject->getLatitide());
	}

	/**
	 * Test if latitude can be set
	 *
	 * @test
	 * @return void
	 */
	public function setLatitudeSetsLatitude() {
		$this->subject->setLatitide(12.345678);
		$this->assertSame(12.345678, $this->subject->getLatitide());
	}
}