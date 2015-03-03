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

use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDateViewHelper;

/**
 * Test case for iCalendar Date viewhelper
 */
class ICalendarDateViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Data Provider for unit tests
	 *
	 * @return array
	 */
	public function iCalendarDateDataProvider() {
		return array(
			'emptyValue' => array(
				'',
				''
			),
			'dateTimeObject' => array(
				new \DateTime('@1425234250'),
				'20150301T182410Z'
			)
		);
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
	public function viewHelperReturnsExpectedValues($value, $expected) {
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
	public function viewHelperRendersChildrenIfNoValueGiven() {
		$viewHelper = $this->getMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Format\\ICalendarDateViewHelper',
			array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(new \DateTime('@1425234250')));
		$actual = $viewHelper->render();
		$this->assertSame('20150301T182410Z', $actual);
	}

}