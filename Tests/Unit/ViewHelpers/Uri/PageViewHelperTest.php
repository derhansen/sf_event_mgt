<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\ViewHelpers\Uri\PageViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

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
        $mockUriBuilderFrontendUri = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['buildFrontendUri'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderFrontendUri->expects($this->once())->method('buildFrontendUri')->will(
            $this->returnValue('The Uri')
        );

        $mockUriBuilderQueryStringMethod = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setAddQueryStringMethod'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderQueryStringMethod->expects($this->once())->method('setAddQueryStringMethod')->will(
            $this->returnValue($mockUriBuilderFrontendUri)
        );

        $mockUriBuilderExcludedFromQueryString = $this->getMockBuilder(
            UriBuilder::class
        )->setMethods(['setArgumentsToBeExcludedFromQueryString'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderExcludedFromQueryString->expects($this->once())->method('setArgumentsToBeExcludedFromQueryString')->will(
            $this->returnValue($mockUriBuilderQueryStringMethod)
        );

        $mockUriBuilderQueryString = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setAddQueryString'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderQueryString->expects($this->once())->method('setAddQueryString')->will(
            $this->returnValue($mockUriBuilderExcludedFromQueryString)
        );

        $mockUriBuilderAbsoluteUri = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setCreateAbsoluteUri'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderAbsoluteUri->expects($this->once())->method('setCreateAbsoluteUri')->will(
            $this->returnValue($mockUriBuilderQueryString)
        );

        $mockUriBuilderArguments = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderArguments->expects($this->once())->method('setArguments')->will(
            $this->returnValue($mockUriBuilderAbsoluteUri)
        );

        $mockUriBuilderRestrictedPages = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setLinkAccessRestrictedPages'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderRestrictedPages->expects($this->once())->method('setLinkAccessRestrictedPages')->will(
            $this->returnValue($mockUriBuilderArguments)
        );

        $mockUriBuilderSection = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setSection'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderSection->expects($this->once())->method('setSection')->will(
            $this->returnValue($mockUriBuilderRestrictedPages)
        );

        $mockUriBuilderCacheHash = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderCacheHash->expects($this->once())->method('setUseCacheHash')->will(
            $this->returnValue($mockUriBuilderSection)
        );

        $mockUriBuilderNoCache = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setNoCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderNoCache->expects($this->once())->method('setNoCache')->will(
            $this->returnValue($mockUriBuilderCacheHash)
        );

        $mockUriBuilderPageType = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setTargetPageType'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderPageType->expects($this->once())->method('setTargetPageType')->will(
            $this->returnValue($mockUriBuilderNoCache)
        );

        $mockUriBuilderPageUid = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setTargetPageUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockUriBuilderPageUid->expects($this->once())->method('setTargetPageUid')->will(
            $this->returnValue($mockUriBuilderPageType)
        );

        $mockControllerContext = $this->getMockBuilder(ControllerContext::class)
            ->setMethods(['getUriBuilder'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockControllerContext->expects($this->once())->method('getUriBuilder')->will(
            $this->returnValue($mockUriBuilderPageUid)
        );

        $viewHelper = $this->getAccessibleMock(
            PageViewHelper::class,
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
        $mockTimeTracker = $this->getMockBuilder(TimeTracker::class)
            ->setMethods(['start'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockTimeTracker->expects($this->once())->method('start');

        $mockTsfe = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->setMethods(
                [
                    'initFEuser',
                    'fetch_the_id',
                    'initTemplate',
                    'getConfigArray'
                ]
            )->disableOriginalConstructor()
            ->getMock();
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock(
            PageViewHelper::class,
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
        $mockTsfe = $this->getMockBuilder(TypoScriptFrontendController::class)
            ->setMethods(
                [
                    'initFEuser',
                    'fetch_the_id',
                    'initTemplate',
                    'getConfigArray'
                ]
            )->disableOriginalConstructor()
            ->getMock();
        $mockTsfe->expects($this->once())->method('initFEuser');
        $mockTsfe->expects($this->once())->method('fetch_the_id');
        $mockTsfe->expects($this->once())->method('initTemplate');
        $mockTsfe->expects($this->once())->method('getConfigArray');

        $viewHelper = $this->getAccessibleMock(
            PageViewHelper::class,
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
            PageViewHelper::class,
            ['dummy'],
            [],
            '',
            false
        );
        $result = $viewHelper->_call('getTsfeInstance');
        $this->assertInstanceOf(TypoScriptFrontendController::class, $result);
    }

    /**
     * @test
     * @return void
     */
    public function getTimeTrackerInstanceReturnsInstanceOfTsfeController()
    {
        $viewHelper = $this->getAccessibleMock(
            PageViewHelper::class,
            ['dummy'],
            [],
            '',
            false
        );
        $result = $viewHelper->_call('getTimeTrackerInstance');
        $this->assertInstanceOf(TimeTracker::class, $result);
    }
}
