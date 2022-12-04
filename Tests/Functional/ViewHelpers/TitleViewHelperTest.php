<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class TitleViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected StandaloneView $view;

    public function setUp(): void
    {
        parent::setUp();

        // Default LANG just returns incoming value as label if calling ->sL()
        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::any())->method('loadSingleTableDescription')->willReturn(null);
        $languageService->expects(self::any())->method('sL')->willReturn('foo');
        $GLOBALS['LANG'] = $languageService;

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->getRenderingContext()->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<e:title indexedDocTitle="{title}"/>');
    }

    /**
     * @test
     */
    public function indexedSearchTitleIsSet()
    {
        $GLOBALS['TSFE'] = $this->createMock(TypoScriptFrontendController::class);

        self::assertEmpty($this->view->assign('title', 'Test')->render());
        self::assertEquals('Test', $GLOBALS['TSFE']->indexedDocTitle);
    }
}
