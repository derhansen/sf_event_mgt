<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use TYPO3\CMS\Core\PageTitle\PageTitleProviderManager;
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

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->getRenderingContext()->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<e:title pageTitle="{title}"/>');

        $tsfe = $this->createMock(TypoScriptFrontendController::class);
        $tsfe->config['config'] = [
            'pageTitleProviders' => [
                'tx_sfeventmgt' => [
                    'provider' => 'DERHANSEN\SfEventMgt\PageTitle\EventPageTitleProvider',
                    'before' => 'record',
                ],
            ],
        ];
        $GLOBALS['TSFE'] = $tsfe;
    }

    /**
     * @test
     */
    public function viewHelperReturnsExpectedResult(): void
    {
        $this->assertEmpty($this->view->assign('title', 'Test')->render());

        $titleProvider = GeneralUtility::makeInstance(PageTitleProviderManager::class);
        $title = $titleProvider->getTitle();

        self::assertEquals('Test', $title);
    }
}
