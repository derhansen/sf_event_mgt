<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

/*
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
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
