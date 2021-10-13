<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test for TitleViewHelper
 */
class TitleViewHelperTest extends FunctionalTestCase
{
    use ProphecyTrait;

    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected StandaloneView $view;

    public function setUp(): void
    {
        parent::setUp();

        // Default LANG prophecy just returns incoming value as label if calling ->sL()
        $languageServiceProphecy = $this->prophesize(LanguageService::class);
        $languageServiceProphecy->loadSingleTableDescription(Argument::cetera())->willReturn(null);
        $languageServiceProphecy->sL(Argument::cetera())->willReturnArgument(0);
        $GLOBALS['LANG'] = $languageServiceProphecy->reveal();

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->getRenderingContext()->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()->setTemplateSource('<e:title indexedDocTitle="{title}"/>');
    }

    /**
     * @test
     */
    public function indexedSearchTitleIsSet()
    {
        self::assertEmpty($this->view->assign('title', 'Test')->render());
        self::assertEquals('Test', $GLOBALS['TSFE']->indexedDocTitle);
    }
}
