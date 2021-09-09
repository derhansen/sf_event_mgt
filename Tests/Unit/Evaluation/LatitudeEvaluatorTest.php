<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

use DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator
 */
class LatitudeEvaluatorTest extends UnitTestCase
{
    protected LatitudeEvaluator $subject;

    protected function setUp(): void
    {
        $this->subject = new LatitudeEvaluator();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    public function latitudeEvaluatorDataProvider(): array
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
     * @test
     * @dataProvider latitudeEvaluatorDataProvider
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function latitudeEvaluatorTest($value, $expected)
    {
        $set = null;
        $actual = $this->subject->evaluateFieldValue($value, null, $set);
        self::assertSame($actual, $expected);
    }
}
