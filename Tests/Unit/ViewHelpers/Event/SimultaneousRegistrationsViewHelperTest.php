<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Event;

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

/**
 * Test cases for SimultaneousRegistrationsViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SimultaneousRegistrationsViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Viewhelper
	 *
	 * @var \DERHANSEN\SfEventMgt\ViewHelpers\Event\SimultaneousRegistrationsViewHelper
	 */
	protected $viewhelper = NULL;

	/**
	 * Setup
	 *
	 * @return void
	 */
	protected function setUp() {
		$this->viewhelper = new \DERHANSEN\SfEventMgt\ViewHelpers\Event\SimultaneousRegistrationsViewHelper();
	}

	/**
	 * Teardown
	 *
	 * @return void
	 */
	protected function tearDown() {
		unset($this->viewhelper);
	}

	/**
	 * Data provider for simultaneousRegistrations
	 *
	 * @return array
	 */
	public function simultaneousRegistrationsDataProvider(){
		return array(
			'maxRegistrationsAndFreePlacesEqual' => array(
				1,
				1,
				array(
					1 => 1
				)
			),
			'moreMaxRegistrationsThanFreePlaces' => array(
				1,
				2,
				array(
					1 => 1
				)
			),
			'moreFreePlacesThanMaxRegistrations' => array(
				10,
				1,
				array(
					1 => 1
				)
			),
			'moreFreePlacesThanMaxRegistrationsWithSimultaneousAllowed' => array(
				10,
				5,
				array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5
				)
			),
			'noFreePlacesAvailable' => array(
				0,
				1,
				array(
					0 => 0
				)
			),
			'noFreePlacesAndNoMaxRegistrations' => array(
				0,
				0,
				array(
					0 => 0
				)
			),
		);
	}
	
	/**
	 * @test
	 * @dataProvider simultaneousRegistrationsDataProvider
	 * @return void
	 */
	public function viewHelperReturnsExpectedValues($freePlaces, $maxRegistrations, $expected) {
		$mockEvent = $this->getMock('DERHANSEN\\SfEventMgt\\Domain\\Model\\Event', array());
		$mockEvent->expects($this->once())->method('getFreePlaces')->will($this->returnValue($freePlaces));
		$mockEvent->expects($this->any())->method('getMaxRegistrationsPerUser')->will($this->returnValue($maxRegistrations));
		$actual = $this->viewhelper->render($mockEvent);
		$this->assertEquals($expected, $actual);
	}

}