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

/**
 * Test case for uri.page viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PageViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Test if all methods from UriBuilder are called and that UriBuilder creates a frontend uri
     *
     * @test
     * @return void
     */
    public function viewHelperCallsBuildFrontendUri()
    {
        $mockUriBuilderFrontendUri = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['buildFrontendUri'], [], '', false);
        $mockUriBuilderFrontendUri->expects($this->once())->method('buildFrontendUri')->will(
            $this->returnValue('The Uri'));

        $mockUriBuilderQueryStringMethod = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setAddQueryStringMethod'], [], '', false);
        $mockUriBuilderQueryStringMethod->expects($this->once())->method('setAddQueryStringMethod')->will(
            $this->returnValue($mockUriBuilderFrontendUri));

        $mockUriBuilderExcludedFromQueryString = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setArgumentsToBeExcludedFromQueryString'], [], '', false);
        $mockUriBuilderExcludedFromQueryString->expects($this->once())->method('setArgumentsToBeExcludedFromQueryString')->will(
            $this->returnValue($mockUriBuilderQueryStringMethod));

        $mockUriBuilderQueryString = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setAddQueryString'], [], '', false);
        $mockUriBuilderQueryString->expects($this->once())->method('setAddQueryString')->will(
            $this->returnValue($mockUriBuilderExcludedFromQueryString));

        $mockUriBuilderAbsoluteUri = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setCreateAbsoluteUri'], [], '', false);
        $mockUriBuilderAbsoluteUri->expects($this->once())->method('setCreateAbsoluteUri')->will(
            $this->returnValue($mockUriBuilderQueryString));

        $mockUriBuilderArguments = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setArguments'], [], '', false);
        $mockUriBuilderArguments->expects($this->once())->method('setArguments')->will(
            $this->returnValue($mockUriBuilderAbsoluteUri));

        $mockUriBuilderRestrictedPages = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setLinkAccessRestrictedPages'], [], '', false);
        $mockUriBuilderRestrictedPages->expects($this->once())->method('setLinkAccessRestrictedPages')->will(
            $this->returnValue($mockUriBuilderArguments));

        $mockUriBuilderSection = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setSection'], [], '', false);
        $mockUriBuilderSection->expects($this->once())->method('setSection')->will(
            $this->returnValue($mockUriBuilderRestrictedPages));

        $mockUriBuilderCacheHash = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setUseCacheHash'], [], '', false);
        $mockUriBuilderCacheHash->expects($this->once())->method('setUseCacheHash')->will(
            $this->returnValue($mockUriBuilderSection));

        $mockUriBuilderNoCache = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setNoCache'], [], '', false);
        $mockUriBuilderNoCache->expects($this->once())->method('setNoCache')->will(
            $this->returnValue($mockUriBuilderCacheHash));

        $mockUriBuilderPageType = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setTargetPageType'], [], '', false);
        $mockUriBuilderPageType->expects($this->once())->method('setTargetPageType')->will(
            $this->returnValue($mockUriBuilderNoCache));

        $mockUriBuilderPageUid = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder',
            ['setTargetPageUid'], [], '', false);
        $mockUriBuilderPageUid->expects($this->once())->method('setTargetPageUid')->will(
            $this->returnValue($mockUriBuilderPageType));

        $mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
            ['getUriBuilder'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getUriBuilder')->will(
            $this->returnValue($mockUriBuilderPageUid));

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['buildTsfe'], [], '', false);
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
    public function buildTsfeWithoutTtSet()
    {
        $mockTimeTracker = $this->getMock('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker',
            ['start'], [], '', false);
        $mockTimeTracker->expects($this->once())->method('start');

        $mockTsfe = $this->getMock(
            'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', [
            'initFEuser',
            'fetch_the_id',
            'getPageAndRootline',
            'initTemplate',
            'getConfigArray'
        ], [], '', false);
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('getPageAndRootline');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['getTsfeInstance', 'getTimeTrackerInstance'], [], '', false);
        $viewHelper->expects($this->once())->method('getTimeTrackerInstance')->will(
            $this->returnValue($mockTimeTracker));
        $viewHelper->expects($this->once())->method('getTsfeInstance')->will($this->returnValue($mockTsfe));
        $viewHelper->_call('buildTsfe');
    }

    /**
     * @test
     * @return void
     */
    public function buildTsfeWithTtSet()
    {
        $GLOBALS['TT'] = new \stdClass();
        $mockTsfe = $this->getMock(
            'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', [
            'initFEuser',
            'fetch_the_id',
            'getPageAndRootline',
            'initTemplate',
            'getConfigArray'
        ], [], '', false);
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('getPageAndRootline');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['getTsfeInstance', 'getTimeTrackerInstance'], [], '', false);
        $viewHelper->expects($this->once())->method('getTsfeInstance')->will($this->returnValue($mockTsfe));
        $viewHelper->_call('buildTsfe');
    }

    /**
     * @test
     * @return void
     */
    public function getTsfeInstanceReturnsInstanceOfTsfeController()
    {
        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['dummy'], [], '', false);
        $result = $viewHelper->_call('getTsfeInstance');
        $this->assertInstanceOf('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $result);
    }

    /**
     * @test
     * @return void
     */
    public function getTimeTrackerInstanceReturnsInstanceOfTsfeController()
    {
        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['dummy'], [], '', false);
        $result = $viewHelper->_call('getTimeTrackerInstance');
        $this->assertInstanceOf('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker', $result);
    }

}