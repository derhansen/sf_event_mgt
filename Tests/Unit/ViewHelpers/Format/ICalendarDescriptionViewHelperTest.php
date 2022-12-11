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

class ICalendarDescriptionViewHelperTest extends UnitTestCase
{
    public function iCalendarDescriptionDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                12,
                '',
            ],
            'shortDescriptionLess75Chars' => [
                'This is just a short text with less than 75 chars',
                12,
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtml' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars',
                12,
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtmlAndLineBreak' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars' . chr(13) . ' and some more text',
                12,
                'This is just a short text with less than 75 chars\n\n and some ' . chr(10) . ' more text',
            ],
            'longDescriptionWithoutLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                12,
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, s' . chr(10) . ' ed diam',
            ],
            'longDescriptionWithLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam ' . chr(13) . 'nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                12,
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am \n\nnonumy eirmod tempor invidunt ut labore et dolore magna aliquyam era' . chr(10) . ' t, sed diam',
            ],
            'longDescriptionWithDifferentSubstractCharsOption' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                48,
                'Lorem ipsum dolor sit amet,' . chr(10) . '  consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut lab' . chr(10) . ' ore et dolore magna aliquyam erat, sed diam',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider iCalendarDescriptionDataProvider
     */
    public function viewHelperReturnsExpectedValues(string $value, int $substractChars, string $expected): void
    {
        $viewHelper = new ICalendarDescriptionViewHelper();
        $viewHelper->setArguments(['description' => $value, 'substractChars' => $substractChars]);
        $actual = $viewHelper->render();
        self::assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function viewHelperRendersChildrenIfNoValueGiven(): void
    {
        $viewHelper = $this->getMockBuilder(ICalendarDescriptionViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')->willReturn('Just some text');
        $actual = $viewHelper->render();
        self::assertSame('Just some text', $actual);
    }
}
