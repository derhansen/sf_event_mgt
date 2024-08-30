<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Category;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class CountViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected StandaloneView $view;

    public function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../../Fixtures/events_findbycategory.csv');

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->getRenderingContext()->getViewHelperResolver()
            ->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $this->view->getRenderingContext()->getTemplatePaths()
            ->setTemplateSource('<e:category.count categoryUid="{categoryUid}"/>');
    }

    #[Test]
    public function viewHelperReturnsExpectedResult(): void
    {
        $result = $this->view->assign('categoryUid', 5)->render();
        self::assertEquals(4, $result);
    }
}
