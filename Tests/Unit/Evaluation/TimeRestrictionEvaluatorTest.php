<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Evaluation;

use DERHANSEN\SfEventMgt\Evaluation\TimeRestrictionEvaluator;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Evaluation\TimeRestrictionEvaluator
 */
class TimeRestrictionEvaluatorTest extends UnitTestCase
{
    protected TimeRestrictionEvaluator $subject;

    protected function setUp(): void
    {
        $this->subject = new TimeRestrictionEvaluator();
        $GLOBALS['LANG'] = $this->getMockBuilder(LanguageService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sL'])
            ->getMock();
        $GLOBALS['LANG']->expects(self::any())->method('sL')->willReturn('test');
    }

    protected function tearDown(): void
    {
        unset($this->subject);
        unset($GLOBALS['LANG']);
    }

    public function timeRestrictionEvaluatorDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                true
            ],
            'validValue' => [
                'today',
                true
            ],
            'invalidValue' => [
                'foo',
                false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider timeRestrictionEvaluatorDataProvider
     *
     * @param string $value
     * @param string $expected
     */
    public function timeRestrictionEvaluatorTest($value, $expected)
    {
        $set = true;
        $returnValue = $this->subject->evaluateFieldValue($value, '', $set);
        self::assertSame($value, $returnValue);
        self::assertSame($set, $expected);
    }
}
