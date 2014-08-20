<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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

use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;

/**
 * Test case for prefill viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsEmptyStringIfTsfeNotAvailabe() {
		$viewHelper = new PrefillViewHelper();
		$actual = $viewHelper->render('a field', array());
		$this->assertSame('', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsEmptyStringIfNoTsfeLoginuserNotAvailabe() {
		$GLOBALS['TSFE'] = new \stdClass();
		$viewHelper = new PrefillViewHelper();
		$actual = $viewHelper->render('a field', array());
		$this->assertSame('', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsEmptyStringIfPrefillSettingsEmpty() {
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->loginUser = 1;
		$viewHelper = new PrefillViewHelper();
		$actual = $viewHelper->render('a field', array());
		$this->assertSame('', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsEmptyStringIfFieldNotFoundInPrefillSettings() {
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->loginUser = 1;
		$viewHelper = new PrefillViewHelper();
		$actual = $viewHelper->render('lastname', array('firstname' => 'first_name'));
		$this->assertSame('', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsEmptyStringIfFieldNotFoundInFeUser() {
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->loginUser = 1;
		$GLOBALS['TSFE']->fe_user = new \stdClass();
		$GLOBALS['TSFE']->fe_user->user = array(
			'first_name' => 'John'
		);

		$mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
			array('getOriginalRequest'), array(), '', FALSE);
		$mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(NULL));

		$mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
			array('getRequest'), array(), '', FALSE);
		$mockControllerContext->expects($this->once())->method('getRequest')->will(
			$this->returnValue($mockRequest));

		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
			array('dummy'), array(), '', FALSE);
		$viewHelper->_set('controllerContext', $mockControllerContext);
		$actual = $viewHelper->render('firstname', array('firstname' => 'unknown_field'));
		$this->assertSame('', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsFieldvalueIfFound() {
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->loginUser = 1;
		$GLOBALS['TSFE']->fe_user = new \stdClass();
		$GLOBALS['TSFE']->fe_user->user = array(
			'first_name' => 'John',
			'last_name' => 'Doe'
		);
		$mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
			array('getOriginalRequest'), array(), '', FALSE);
		$mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(NULL));

		$mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
			array('getRequest'), array(), '', FALSE);
		$mockControllerContext->expects($this->once())->method('getRequest')->will(
			$this->returnValue($mockRequest));

		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
			array('dummy'), array(), '', FALSE);
		$viewHelper->_set('controllerContext', $mockControllerContext);
		$actual = $viewHelper->render('lastname', array('lastname' => 'last_name'));
		$this->assertSame('Doe', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function viewReturnsSubmittedValueIfValidationError() {
		$GLOBALS['TSFE'] = new \stdClass();
		$GLOBALS['TSFE']->loginUser = 1;
		$GLOBALS['TSFE']->fe_user = new \stdClass();
		$GLOBALS['TSFE']->fe_user->user = array(
			'first_name' => 'John',
			'last_name' => 'Doe'
		);

		$arguments = array(
			'registration' => array(
				'lastname' => 'Submitted Lastname'
			)
		);

		$mockOriginalRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
			array('getArguments'), array(), '', FALSE);
		$mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

		$mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
			array('getOriginalRequest'), array(), '', FALSE);
		$mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue($mockOriginalRequest));

		$mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
			array('getRequest'), array(), '', FALSE);
		$mockControllerContext->expects($this->once())->method('getRequest')->will(
			$this->returnValue($mockRequest));

		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
			array('dummy'), array(), '', FALSE);
		$viewHelper->_set('controllerContext', $mockControllerContext);
		$actual = $viewHelper->render('lastname', array('lastname' => 'last_name'));
		$this->assertSame('Submitted Lastname', $actual);
	}
}