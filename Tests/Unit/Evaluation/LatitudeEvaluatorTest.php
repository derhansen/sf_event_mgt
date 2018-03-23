<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LatitudeEvaluatorTest extends UnitTestCase
{
    /**
     * LatitudeEvaluator
     *
     * @var \DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Data Provider for latitudeEvaluatorTest
     *
     * @return array
     */
    public function latitudeEvaluatorDataProvider()
    {
        return [
            'emptyValue' => [
                null,
                '0.000000'
            ],
            'givenIntegerGetsConvertedToFloatWith6Decimals' => [
                1,
                '1.000000'
            ],
            'maxValuePossible' => [
                90,
                '90.000000'
            ],
            'minValuePossible' => [
                -90,
                '-90.000000'
            ],
            'greaterThanMaxValueNotPossible' => [
                90.000001,
                '0.000000'
            ],
            'lessThanMinValueNotPossible' => [
                -90.000001,
                '0.000000'
            ],
            'validLongitudeIsReturned' => [
                12.345678,
                '12.345678'
            ],
        ];
    }

    /**
     * Tests for latitudeEvaluator with the given dataProvider
     *
     * @test
     * @dataProvider latitudeEvaluatorDataProvider
     *
     * @param mixed $value
     * @param mixed $expected
     * @return void
     */
    public function latitudeEvaluatorTest($value, $expected)
    {
        $set = null;
        $actual = $this->subject->evaluateFieldValue($value, null, $set);
        $this->assertSame($actual, $expected);
    }
}
