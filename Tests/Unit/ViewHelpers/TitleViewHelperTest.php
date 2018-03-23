<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\ViewHelpers\TitleViewHelper;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Test for TitleViewHelper
 */
class TitleViewHelperTest extends UnitTestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController|AccessibleMockObjectInterface
     */
    protected $tsfe = null;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->tsfe = $this->getAccessibleMock(TypoScriptFrontendController::class, ['dummy'], [], '', false);
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * @test
     */
    public function pageTitleIsSet()
    {
        $pageTitle = 'The event title';
        /** @var TitleViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(TitleViewHelper::class, ['dummy']);
        $viewHelper::renderStatic(
            [
                'pageTitle' => $pageTitle
            ],
            function () {
                return '';
            },
            $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock()
        );
        $this->assertEquals($pageTitle, $GLOBALS['TSFE']->altPageTitle);
        $this->assertEquals($pageTitle, $GLOBALS['TSFE']->indexedDocTitle);
    }

    /**
     * @test
     */
    public function indexedSearchTitleIsSet()
    {
        $pageTitle = 'The event title for the page title';
        $indexedSearchDocTitle = 'The event title for indexed search';
        /** @var TitleViewHelper|\PHPUnit_Framework_MockObject_MockObject $viewHelper */
        $viewHelper = $this->getAccessibleMock(TitleViewHelper::class, ['dummy']);
        $viewHelper::renderStatic(
            [
                'pageTitle' => $pageTitle,
                'indexedDocTitle' => $indexedSearchDocTitle
            ],
            function () {
                return '';
            },
            $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock()
        );
        $this->assertEquals($pageTitle, $GLOBALS['TSFE']->altPageTitle);
        $this->assertEquals($indexedSearchDocTitle, $GLOBALS['TSFE']->indexedDocTitle);
    }
}
