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

/**
 * Test case for uri.page viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PageViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * Test if all methods from UriBuilder are called and that UriBuilder creates a frontend uri
	 *
	 * @test
	 * @return void
	 */
	public function viewHelperCallsBuildFrontendUri() {
		$mockUriBuilderFrontendUri = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('buildFrontendUri'), array(), '', FALSE);
		$mockUriBuilderFrontendUri->expects($this->once())->method('buildFrontendUri')->will(
			$this->returnValue('The Uri'));

		$mockUriBuilderQueryStringMethod = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setAddQueryStringMethod'), array(), '', FALSE);
		$mockUriBuilderQueryStringMethod->expects($this->once())->method('setAddQueryStringMethod')->will(
			$this->returnValue($mockUriBuilderFrontendUri));

		$mockUriBuilderExcludedFromQueryString = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setArgumentsToBeExcludedFromQueryString'), array(), '', FALSE);
		$mockUriBuilderExcludedFromQueryString->expects($this->once())->method('setArgumentsToBeExcludedFromQueryString')->will(
			$this->returnValue($mockUriBuilderQueryStringMethod));

		$mockUriBuilderQueryString = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setAddQueryString'), array(), '', FALSE);
		$mockUriBuilderQueryString->expects($this->once())->method('setAddQueryString')->will(
			$this->returnValue($mockUriBuilderExcludedFromQueryString));

		$mockUriBuilderAbsoluteUri = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setCreateAbsoluteUri'), array(), '', FALSE);
		$mockUriBuilderAbsoluteUri->expects($this->once())->method('setCreateAbsoluteUri')->will(
			$this->returnValue($mockUriBuilderQueryString));

		$mockUriBuilderArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setArguments'), array(), '', FALSE);
		$mockUriBuilderArguments->expects($this->once())->method('setArguments')->will(
			$this->returnValue($mockUriBuilderAbsoluteUri));

		$mockUriBuilderRestrictedPages = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setLinkAccessRestrictedPages'), array(), '', FALSE);
		$mockUriBuilderRestrictedPages->expects($this->once())->method('setLinkAccessRestrictedPages')->will(
			$this->returnValue($mockUriBuilderArguments));

		$mockUriBuilderSection = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setSection'), array(), '', FALSE);
		$mockUriBuilderSection->expects($this->once())->method('setSection')->will(
			$this->returnValue($mockUriBuilderRestrictedPages));

		$mockUriBuilderCacheHash = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setUseCacheHash'), array(), '', FALSE);
		$mockUriBuilderCacheHash->expects($this->once())->method('setUseCacheHash')->will(
			$this->returnValue($mockUriBuilderSection));

		$mockUriBuilderNoCache = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setNoCache'), array(), '', FALSE);
		$mockUriBuilderNoCache->expects($this->once())->method('setNoCache')->will(
			$this->returnValue($mockUriBuilderCacheHash));

		$mockUriBuilderPageType = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setTargetPageType'), array(), '', FALSE);
		$mockUriBuilderPageType->expects($this->once())->method('setTargetPageType')->will(
			$this->returnValue($mockUriBuilderNoCache));

		$mockUriBuilderPageUid = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
			array('setTargetPageUid'), array(), '', FALSE);
		$mockUriBuilderPageUid->expects($this->once())->method('setTargetPageUid')->will(
			$this->returnValue($mockUriBuilderPageType));

		$mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
			array('getUriBuilder'), array(), '', FALSE);
		$mockControllerContext->expects($this->once())->method('getUriBuilder')->will(
			$this->returnValue($mockUriBuilderPageUid));

		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
			array('buildTSFE'), array(), '', FALSE);
		$viewHelper->_set('controllerContext', $mockControllerContext);
		$viewHelper->expects($this->once())->method('buildTSFE');

		// Just callrender method - parameters do not matter in this case,
		// since everything is mocked
		$actual = $viewHelper->render();
		$this->assertSame('The Uri', $actual);
	}

	/**
	 * @test
	 * @return void
	 */
	public function buildTsfeWithoutTtSet() {
		$mockTimeTracker = $this->getMock('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker',
			array('start'), array(), '', FALSE);
		$mockTimeTracker->expects($this->once())->method('start');

		$mockTsfe = $this->getMock(
			'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', array('initFEuser', 'fetch_the_id',
			'getPageAndRootline', 'initTemplate', 'getConfigArray'), array(), '', FALSE);
		$mockTsfe->expects($this->once())->method('initFEuser');
		$mockTsfe->expects($this->once())->method('fetch_the_id');
		$mockTsfe->expects($this->once())->method('getPageAndRootline');
		$mockTsfe->expects($this->once())->method('initTemplate');
		$mockTsfe->expects($this->once())->method('getConfigArray');

		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
			array('getTsfeInstance', 'getTimeTrackerInstance'), array(), '', FALSE);
		$viewHelper->expects($this->once())->method('getTimeTrackerInstance')->will(
			$this->returnValue($mockTimeTracker));
		$viewHelper->expects($this->once())->method('getTsfeInstance')->will($this->returnValue($mockTsfe));
		$viewHelper->_call('buildTSFE');
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTsfeInstanceReturnsInstanceOfTsfeController() {
		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
			array('dummy'), array(), '', FALSE);
		$result = $viewHelper->_call('getTsfeInstance');
		$this->assertInstanceOf('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $result);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTimeTrackerInstanceReturnsInstanceOfTsfeController() {
		$viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
			array('dummy'), array(), '', FALSE);
		$result = $viewHelper->_call('getTimeTrackerInstance');
		$this->assertInstanceOf('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker', $result);
	}

}