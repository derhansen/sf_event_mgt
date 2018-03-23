<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for uri.page viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PageViewHelperTest extends UnitTestCase
{
    /**
     * Test if all methods from UriBuilder are called and that UriBuilder creates a frontend uri
     *
     * @test
     * @return void
     */
    public function viewHelperCallsBuildFrontendUri()
    {
        $mockUriBuilderFrontendUri = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['buildFrontendUri'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderFrontendUri->expects($this->once())->method('buildFrontendUri')->will(
            $this->returnValue('The Uri')
        );

        $mockUriBuilderQueryStringMethod = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setAddQueryStringMethod'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderQueryStringMethod->expects($this->once())->method('setAddQueryStringMethod')->will(
            $this->returnValue($mockUriBuilderFrontendUri)
        );

        $mockUriBuilderExcludedFromQueryString = $this->getMockBuilder(
            'TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder'
        )->setMethods(['setArgumentsToBeExcludedFromQueryString'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderExcludedFromQueryString->expects($this->once())->method('setArgumentsToBeExcludedFromQueryString')->will(
            $this->returnValue($mockUriBuilderQueryStringMethod)
        );

        $mockUriBuilderQueryString = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setAddQueryString'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderQueryString->expects($this->once())->method('setAddQueryString')->will(
            $this->returnValue($mockUriBuilderExcludedFromQueryString)
        );

        $mockUriBuilderAbsoluteUri = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setCreateAbsoluteUri'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderAbsoluteUri->expects($this->once())->method('setCreateAbsoluteUri')->will(
            $this->returnValue($mockUriBuilderQueryString)
        );

        $mockUriBuilderArguments = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderArguments->expects($this->once())->method('setArguments')->will(
            $this->returnValue($mockUriBuilderAbsoluteUri)
        );

        $mockUriBuilderRestrictedPages = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setLinkAccessRestrictedPages'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderRestrictedPages->expects($this->once())->method('setLinkAccessRestrictedPages')->will(
            $this->returnValue($mockUriBuilderArguments)
        );

        $mockUriBuilderSection = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setSection'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderSection->expects($this->once())->method('setSection')->will(
            $this->returnValue($mockUriBuilderRestrictedPages)
        );

        $mockUriBuilderCacheHash = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setUseCacheHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderCacheHash->expects($this->once())->method('setUseCacheHash')->will(
            $this->returnValue($mockUriBuilderSection)
        );

        $mockUriBuilderNoCache = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setNoCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderNoCache->expects($this->once())->method('setNoCache')->will(
            $this->returnValue($mockUriBuilderCacheHash)
        );

        $mockUriBuilderPageType = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setTargetPageType'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderPageType->expects($this->once())->method('setTargetPageType')->will(
            $this->returnValue($mockUriBuilderNoCache)
        );

        $mockUriBuilderPageUid = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder')
            ->setMethods(['setTargetPageUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderPageUid->expects($this->once())->method('setTargetPageUid')->will(
            $this->returnValue($mockUriBuilderPageType)
        );

        $mockControllerContext = $this->getMockBuilder('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext')
            ->setMethods(['getUriBuilder'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockControllerContext->expects($this->once())->method('getUriBuilder')->will(
            $this->returnValue($mockUriBuilderPageUid)
        );

        $viewHelper = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['buildTsfe'],
            [],
            '',
            false
        );
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
        $mockTimeTracker = $this->getMockBuilder('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker')
            ->setMethods(['start'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockTimeTracker->expects($this->once())->method('start');

        $mockTsfe = $this->getMockBuilder('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController')
            ->setMethods(
                [
                    'initFEuser',
                    'fetch_the_id',
                    'getPageAndRootline',
                    'initTemplate',
                    'getConfigArray'
                ]
            )->disableOriginalConstructor()
            ->getMock();
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('getPageAndRootline');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['getTsfeInstance', 'getTimeTrackerInstance'],
            [],
            '',
            false
        );
        $viewHelper->expects($this->once())->method('getTimeTrackerInstance')->will(
            $this->returnValue($mockTimeTracker)
        );
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
        $mockTsfe = $this->getMockBuilder('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController')
            ->setMethods(
                [
                    'initFEuser',
                    'fetch_the_id',
                    'getPageAndRootline',
                    'initTemplate',
                    'getConfigArray'
                ]
            )->disableOriginalConstructor()
            ->getMock();
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('getPageAndRootline');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['getTsfeInstance', 'getTimeTrackerInstance'],
            [],
            '',
            false
        );
        $viewHelper->expects($this->once())->method('getTsfeInstance')->will($this->returnValue($mockTsfe));
        $viewHelper->_call('buildTsfe');
    }

    /**
     * @test
     * @return void
     */
    public function getTsfeInstanceReturnsInstanceOfTsfeController()
    {
        $viewHelper = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['dummy'],
            [],
            '',
            false
        );
        $result = $viewHelper->_call('getTsfeInstance');
        $this->assertInstanceOf('TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController', $result);
    }

    /**
     * @test
     * @return void
     */
    public function getTimeTrackerInstanceReturnsInstanceOfTsfeController()
    {
        $viewHelper = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\ViewHelpers\\Uri\\PageViewHelper',
            ['dummy'],
            [],
            '',
            false
        );
        $result = $viewHelper->_call('getTimeTrackerInstance');
        $this->assertInstanceOf('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker', $result);
    }
}
