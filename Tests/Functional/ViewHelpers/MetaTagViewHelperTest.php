<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class MetaTagViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected StandaloneView $view;
    protected PageRenderer $pageRenderer;

    public function setUp(): void
    {
        parent::setUp();

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
    }

    /**
     * @test
     */
    public function metaTagForNameIsSetByViewHelper()
    {
        $this->view->getRenderingContext()->getViewHelperResolver()
            ->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()
            ->setTemplateSource('<e:metaTag name="keywords" content="keyword1, keyword2" />');
        $this->view->render();

        $metaTag = $this->pageRenderer->getMetaTag('name', 'keywords');
        self::assertEquals('keyword1, keyword2', $metaTag['content']);
    }

    /**
     * @test
     */
    public function metaTagForPropertyIsSetByViewHelper()
    {
        $this->view->getRenderingContext()->getViewHelperResolver()
            ->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()
            ->setTemplateSource('<e:metaTag property="og:title" content="The og:title" />');
        $this->view->render();

        $metaTag = $this->pageRenderer->getMetaTag('property', 'og:title');
        self::assertEquals('The og:title', $metaTag['content']);
    }
}
