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

    public static function latitudeEvaluatorDataProvider(): array
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
                '90',
                '90.000000',
            ],
            'min value possible' => [
                '-90',
                '-90.000000',
            ],
            'greater than max value not possible' => [
                '90.000001',
                '0.000000',
            ],
            'less than min value not possible' => [
                '-90.000001',
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
     * @dataProvider latitudeEvaluatorDataProvider
     */
    public function latitudeEvaluatorTest(string $value, string $expected): void
    {
        $set = true;
        $actual = $this->subject->evaluateFieldValue($value, '', $set);
        self::assertSame($actual, $expected);
    }
}
