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
     * @test
     * @return void
     */
    public function viewHelperReturnsExpectedFrontendUrl()
    {
        $this->markTestIncomplete('ViewHelper must be refactored');
    }
}
