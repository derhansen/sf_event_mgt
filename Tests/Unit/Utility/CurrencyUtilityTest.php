<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Utility;

use DERHANSEN\SfEventMgt\Utility\CurrencyUtility;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CurrencyUtilityTest extends UnitTestCase
{
    #[Test]
    public function currencyDataIsResolvedByIsoCode(): void
    {
        $currency = CurrencyUtility::getByIsoCode('EUR');

        $expected = [
            'code' => 'EUR',
            'name' => 'Euro',
            'numeric' => '978',
            'symbol' => '€',
        ];

        self::assertSame($expected, $currency);
    }

    #[Test]
    public function currencyDataIsResolvedBySymbol(): void
    {
        $currency = CurrencyUtility::getBySymbol('€');

        $expected = [
            'code' => 'EUR',
            'name' => 'Euro',
            'numeric' => '978',
            'symbol' => '€',
        ];

        self::assertSame($expected, $currency);
    }
}
