<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDescriptionViewHelper;

/**
 * Test case for iCalendar Description viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDescriptionViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Data Provider for unit tests
     *
     * @return array
     */
    public function iCalendarDescriptionDataProvider()
    {
        return [
            'emptyValue' => [
                '',
                ''
            ],
            'shortDescriptionLess75Chars' => [
                'This is just a short text with less than 75 chars',
                'This is just a short text with less than 75 chars'
            ],
            'shortDescriptionLess75CharsWithHtml' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars',
                'This is just a short text with less than 75 chars'
            ],
            'shortDescriptionLess75CharsWithHtmlAndLineBreak' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars' . chr(13) . ' and some more text',
                'This is just a short text with less than 75 chars\n\n and some more text'
            ],
            'longDescriptionWithoutLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, ' . chr(10) . ' sed diam'
            ],
            'longDescriptionWithLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam ' . chr(13) . 'nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am \n\nnonumy eirmod tempor invidunt ut labore et dolore magna aliquyam er' . chr(10) . ' at, sed diam'
            ]
        ];
    }

    /**
     * Check if the viewhelper returns the expected values
     *
     * @test
     *
     * @dataProvider iCalendarDescriptionDataProvider
     *
     * @return void
     */
    public function viewHelperReturnsExpectedValues($value, $expected)
    {
        $viewHelper = new ICalendarDescriptionViewHelper();
        $actual = $viewHelper->render($value);
        $this->assertEquals($expected, $actual);
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
        $viewHelper = $this->getMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Format\\ICalendarDescriptionViewHelper',
            ['renderChildren']);
        $viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Just some text'));
        $actual = $viewHelper->render();
        $this->assertSame('Just some text', $actual);
    }

}