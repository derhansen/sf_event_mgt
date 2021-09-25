<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\TitleViewHelper;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Fluid\Unit\ViewHelpers\ViewHelperBaseTestcase;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Test for TitleViewHelper
 */
class TitleViewHelperTest extends ViewHelperBaseTestcase
{
    use ProphecyTrait;

    /**
     * @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected $tsfe;

    /**
     * @var TitleViewHelper
     */
    protected $viewHelper;

    /**
     * Set up
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tsfe = $this->prophesize(TypoScriptFrontendController::class)->reveal();
        $GLOBALS['TSFE'] = $this->tsfe;
        $this->viewHelper = new TitleViewHelper();
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->tsfe);
        unset($GLOBALS['TSFE']);
    }

    /**
     * @test
     */
    public function indexedSearchTitleIsSet()
    {
        $pageTitle = 'The event title for the page title';
        $indexedSearchDocTitle = 'The event title for indexed search';
        $this->viewHelper::renderStatic(
            [
                'pageTitle' => $pageTitle,
                'indexedDocTitle' => $indexedSearchDocTitle,
            ],
            function () {
            },
            $this->prophesize(RenderingContextInterface::class)->reveal()
        );
        self::assertEquals($indexedSearchDocTitle, $GLOBALS['TSFE']->indexedDocTitle);
    }
}
