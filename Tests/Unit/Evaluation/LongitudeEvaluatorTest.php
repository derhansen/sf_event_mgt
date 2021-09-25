<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

use DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator
 */
class LongitudeEvaluatorTest extends UnitTestCase
{
    protected LongitudeEvaluator $subject;

    protected function setUp(): void
    {
        $this->subject = new LongitudeEvaluator();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    public function longitudeEvaluatorDataProvider(): array
    {
        return [
            'emptyValue' => [
                null,
                '0.000000',
            ],
            'givenIntegerGetsConvertedToFloatWith6Decimals' => [
                1,
                '1.000000',
            ],
            'maxValuePossible' => [
                180,
                '180.000000',
            ],
            'minValuePossible' => [
                -180,
                '-180.000000',
            ],
            'greaterThanMaxValueNotPossible' => [
                180.000001,
                '0.000000',
            ],
            'lessThanMinValueNotPossible' => [
                -180.000001,
                '0.000000',
            ],
            'validLongitudeIsReturned' => [
                12.345678,
                '12.345678',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider longitudeEvaluatorDataProvider
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function longitudeEvaluatorTest($value, $expected)
    {
        $set = null;
        $actual = $this->subject->evaluateFieldValue($value, null, $set);
        self::assertSame($actual, $expected);
    }
}
