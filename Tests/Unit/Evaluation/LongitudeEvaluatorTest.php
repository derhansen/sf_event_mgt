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
            'empty string' => [
                '',
                '0.000000',
            ],
            'given string gets converted to float with 6 decimals' => [
                '1',
                '1.000000',
            ],
            'max value possible' => [
                '180',
                '180.000000',
            ],
            'min value possible' => [
                '-180',
                '-180.000000',
            ],
            'greater than max value not possible' => [
                '180.000001',
                '0.000000',
            ],
            'less than min value not possible' => [
                '-180.000001',
                '0.000000',
            ],
            'valid longitude is returned' => [
                '12.345678',
                '12.345678',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider longitudeEvaluatorDataProvider
     */
    public function longitudeEvaluatorTest(string $value, string $expected): void
    {
        $set = true;
        $actual = $this->subject->evaluateFieldValue($value, '', $set);
        self::assertSame($actual, $expected);
    }
}
