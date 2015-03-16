<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Torben Hansen <derhansen@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDescriptionViewHelper;

/**
 * Test case for iCalendar Description viewhelper
 */
class ICalendarDescriptionViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Data Provider for unit tests
	 *
	 * @return array
	 */
	public function iCalendarDescriptionDataProvider() {
		return array(
			'emptyValue' => array(
				'',
				''
			),
			'shortDescriptionLess75Chars' => array(
				'This is just a short text with less than 75 chars',
				'This is just a short text with less than 75 chars'
			),
			'shortDescriptionLess75CharsWithHtml' => array(
				'This is just a short text <b>with</b> less&nbsp;than 75 chars',
				'This is just a short text with less than 75 chars'
			),
			'shortDescriptionLess75CharsWithHtmlAndLineBreak' => array(
				'This is just a short text <b>with</b> less&nbsp;than 75 chars' . chr(13) . ' and some more text',
				'This is just a short text with less than 75 chars\n\n and some more text'
			),
			'longDescriptionWithoutLineBreaks' => array(
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, ' . chr(10) . ' sed diam'
			),
			'longDescriptionWithLineBreaks' => array(
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam ' . chr(13) . 'nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
				'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am \n\nnonumy eirmod tempor invidunt ut labore et dolore magna aliquyam er' . chr(10) . ' at, sed diam'
			)
		);
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
	public function viewHelperReturnsExpectedValues($value, $expected) {
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
	public function viewHelperRendersChildrenIfNoValueGiven() {
		$viewHelper = $this->getMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Format\\ICalendarDescriptionViewHelper',
			array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Just some text'));
		$actual = $viewHelper->render();
		$this->assertSame('Just some text', $actual);
	}

}