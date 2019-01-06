<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDateViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for iCalendar Date viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDateViewHelperTest extends UnitTestCase
{
    /**
     * Data Provider for unit tests
     *
     * @return array
     */
    public function iCalendarDateDataProvider()
    {
        return [
            'emptyValue' => [
                '',
                ''
            ],
            'dateTimeObject' => [
                new \DateTime('@1425234250'),
                '20150301T182410Z'
            ]
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
     * @return void
     */
    public function viewHelperReturnsExpectedValues($value, $expected)
    {
        $viewHelper = new ICalendarDateViewHelper();
        $viewHelper->setArguments(['date' => $value]);
        $actual = $viewHelper->render();
        $this->assertSame($expected, $actual);
    }

    /**
     * Check if the viewhelper calls renderChildren if no value given
     *
     * @test
     *
     * @return void
     */
    public function viewHelperRendersChildrenIfNoValueGiven()
    {
        $viewHelper = $this->getMockBuilder(ICalendarDateViewHelper::class)
            ->setMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects($this->once())->method('renderChildren')
            ->will($this->returnValue(new \DateTime('@1425234250')));
        $actual = $viewHelper->render();
        $this->assertSame('20150301T182410Z', $actual);
    }
}
