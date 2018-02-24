<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDateViewHelper;

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
     * @return void
     */
    public function viewHelperReturnsExpectedValues($value, $expected)
    {
        $viewHelper = new ICalendarDateViewHelper();
        $actual = $viewHelper->render($value);
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
        $viewHelper = $this->getMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Format\\ICalendarDateViewHelper',
            ['renderChildren']);
        $viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(new \DateTime('@1425234250')));
        $actual = $viewHelper->render();
        $this->assertSame('20150301T182410Z', $actual);
    }

}