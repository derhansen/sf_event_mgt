<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Torben Hansen <derhansen@gmail.com>
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
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
 */
class LongitudeEvaluatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * LongitudeEvaluator
	 *
	 * @var \DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
	 */
	protected $subject = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->subject = new \DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator();
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
	 * Data Provider for longitudeEvaluatorTest
	 *
	 * @return array
	 */
	public function longitudeEvaluatorDataProvider() {
		return array(
			'emptyValue' => array(
				NULL,
				'0.000000'
			),
			'givenIntegerGetsConvertedToFloatWith6Decimals' => array(
				1,
				'1.000000'
			),
			'maxValuePossible' => array(
				180,
				'180.000000'
			),
			'minValuePossible' => array(
				-180,
				'-180.000000'
			),
			'greaterThanMaxValueNotPossible' => array(
				180.000001,
				'0.000000'
			),
			'lessThanMinValueNotPossible' => array(
				-180.000001,
				'0.000000'
			),
			'validLongitudeIsReturned' => array(
				12.345678,
				'12.345678'
			),
		);
	}

	/**
	 * Tests for longitudeEvaluator with the given dataProvider
	 *
	 * @test
	 * @dataProvider longitudeEvaluatorDataProvider
	 *
	 * @return void
	 */
	public function longitudeEvaluatorTest($value, $expected){
		$set = NULL;
		$actual = $this->subject->evaluateFieldValue($value, NULL, $set);
		$this->assertSame($actual, $expected);
	}

}
