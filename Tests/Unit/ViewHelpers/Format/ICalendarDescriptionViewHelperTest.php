<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDescriptionViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for iCalendar Description viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDescriptionViewHelperTest extends UnitTestCase
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
     * @param mixed $value
     * @param mixed $expected
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
        $viewHelper = $this->getMockBuilder(ICalendarDescriptionViewHelper::class)
            ->setMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Just some text'));
        $actual = $viewHelper->render();
        $this->assertSame('Just some text', $actual);
    }
}
