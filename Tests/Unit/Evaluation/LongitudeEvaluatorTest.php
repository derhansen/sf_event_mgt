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
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LongitudeEvaluatorTest extends UnitTestCase
{

    /**
     * LongitudeEvaluator
     *
     * @var \DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator();
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
     * Data Provider for longitudeEvaluatorTest
     *
     * @return array
     */
    public function longitudeEvaluatorDataProvider()
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
                180,
                '180.000000'
            ],
            'minValuePossible' => [
                -180,
                '-180.000000'
            ],
            'greaterThanMaxValueNotPossible' => [
                180.000001,
                '0.000000'
            ],
            'lessThanMinValueNotPossible' => [
                -180.000001,
                '0.000000'
            ],
            'validLongitudeIsReturned' => [
                12.345678,
                '12.345678'
            ],
        ];
    }

    /**
     * Tests for longitudeEvaluator with the given dataProvider
     *
     * @test
     * @dataProvider longitudeEvaluatorDataProvider
     *
     * @return void
     */
    public function longitudeEvaluatorTest($value, $expected)
    {
        $set = null;
        $actual = $this->subject->evaluateFieldValue($value, null, $set);
        $this->assertSame($actual, $expected);
    }

}
