<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

use DERHANSEN\SfEventMgt\Evaluation\TimeRestrictionEvaluator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class TimeRestrictionEvaluatorTest extends UnitTestCase
{
    protected TimeRestrictionEvaluator $subject;

    protected function setUp(): void
    {
        $this->subject = new TimeRestrictionEvaluator();

        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::any())->method('translate')->willReturn('test');
        $GLOBALS['LANG'] = $languageService;
    }

    protected function tearDown(): void
    {
        unset($this->subject);
        unset($GLOBALS['LANG']);
    }

    public static function timeRestrictionEvaluatorDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                true,
            ],
            'validValue' => [
                'today',
                true,
            ],
            'invalidValue' => [
                'foo',
                false,
            ],
        ];
    }

    #[DataProvider('timeRestrictionEvaluatorDataProvider')]
    #[Test]
    public function timeRestrictionEvaluatorTest(string $value, bool $expected)
    {
        $set = true;
        $returnValue = $this->subject->evaluateFieldValue($value, '', $set);
        self::assertSame($value, $returnValue);
        self::assertSame($set, $expected);
    }
}
