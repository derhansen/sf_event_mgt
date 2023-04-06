<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Utility;

use DERHANSEN\SfEventMgt\Utility\ArrayUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ArrayUtilityTest extends UnitTestCase
{
    public function isJsonArrayReturnsExpectedValuesDataProcessor(): array
    {
        return [
            'null' => [
                null,
                false,
            ],
            'array' => [
                [],
                false,
            ],
            'empty string' => [
                '',
                false,
            ],
            'invalid json' => [
                '{"key": "value"',
                false,
            ],
            'valid json' => [
                '{"key": "value"}',
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isJsonArrayReturnsExpectedValuesDataProcessor
     */
    public function isJsonArrayReturnsExpectedValues($value, $expected): void
    {
        self::assertSame($expected, ArrayUtility::isJsonArray($value));
    }
}
