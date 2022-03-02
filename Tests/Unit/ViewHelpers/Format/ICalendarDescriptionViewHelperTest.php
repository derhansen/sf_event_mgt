<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDescriptionViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for iCalendar Description viewhelper
 */
class ICalendarDescriptionViewHelperTest extends UnitTestCase
{
    public function iCalendarDescriptionDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                '',
            ],
            'shortDescriptionLess75Chars' => [
                'This is just a short text with less than 75 chars',
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtml' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars',
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtmlAndLineBreak' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars' . chr(13) . ' and some more text',
                'This is just a short text with less than 75 chars\n\n and some ' . chr(10) . ' more text',
            ],
            'longDescriptionWithoutLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, s' . chr(10) . ' ed diam',
            ],
            'longDescriptionWithLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam ' . chr(13) . 'nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am \n\nnonumy eirmod tempor invidunt ut labore et dolore magna aliquyam era' . chr(10) . ' t, sed diam',
            ],
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
     */
    public function viewHelperReturnsExpectedValues($value, $expected)
    {
        $viewHelper = new ICalendarDescriptionViewHelper();
        $viewHelper->setArguments(['description' => $value]);
        $actual = $viewHelper->render();
        self::assertEquals($expected, $actual);
    }

    /**
     * Check if the viewhelper calls renderChildren if no value given
     *
     * @test
     */
    public function viewHelperRendersChildrenIfNoValueGiven()
    {
        $viewHelper = $this->getMockBuilder(ICalendarDescriptionViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn('Just some text');
        $actual = $viewHelper->render();
        self::assertSame('Just some text', $actual);
    }
}
