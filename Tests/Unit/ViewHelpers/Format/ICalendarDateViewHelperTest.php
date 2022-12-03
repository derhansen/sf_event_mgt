<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDateViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for iCalendar Date viewhelper
 */
class ICalendarDateViewHelperTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    public function iCalendarDateDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                '',
            ],
            'dateTimeObject' => [
                new \DateTime('@1425234250'),
                '20150301T182410Z',
            ],
        ];
    }

    /**
     * Check if the viewhelper returns the expected values
     *
     * @test
     *
     * @dataProvider iCalendarDateDataProvider
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function viewHelperReturnsExpectedValues($value, $expected)
    {
        $viewHelper = new ICalendarDateViewHelper();
        $viewHelper->setArguments(['date' => $value]);
        $actual = $viewHelper->render();
        self::assertSame($expected, $actual);
    }

    /**
     * Check if the viewhelper calls renderChildren if no value given
     *
     * @test
     */
    public function viewHelperRendersChildrenIfNoValueGiven()
    {
        $viewHelper = $this->getMockBuilder(ICalendarDateViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')
            ->willReturn(new \DateTime('@1425234250'));
        $actual = $viewHelper->render();
        self::assertSame('20150301T182410Z', $actual);
    }
}
