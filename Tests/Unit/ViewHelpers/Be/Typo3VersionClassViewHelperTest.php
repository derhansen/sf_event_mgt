<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Be;

    /*
     * This file is part of the TYPO3 CMS project.
     *
     * It is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License, either version 2
     * of the License, or any later version.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * The TYPO3 project - inspiring people to share!
     */

/**
 * Test cases for Typo3VersionClassViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Typo3VersionClassViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Viewhelper
     *
     * @var \DERHANSEN\SfEventMgt\ViewHelpers\Be\Typo3VersionClassViewHelper
     */
    protected $viewhelper = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->viewhelper = new \DERHANSEN\SfEventMgt\ViewHelpers\Be\Typo3VersionClassViewHelper();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->viewhelper);
    }

    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsExpectedValueForV62()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version'] = '6.2';
        $actual = $this->viewhelper->render();
        $this->assertEquals('typo3-62', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsExpectedValueForV76()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version'] = '7.6';
        $actual = $this->viewhelper->render();
        $this->assertEquals('typo3-76', $actual);
    }
}
