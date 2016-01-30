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
 * Test cases for NewRecordViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EditRecordViewHelperTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * Viewhelper
     *
     * @var \DERHANSEN\SfEventMgt\ViewHelpers\Be\EditRecordViewHelper
     */
    protected $viewhelper = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->viewhelper = new \DERHANSEN\SfEventMgt\ViewHelpers\Be\EditRecordViewHelper();
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
        $uid = 100;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version'] = '6.2';
        $actual = $this->viewhelper->render($uid);
        $expected = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][100]=edit&returnUrl=mod.php' .
            '%3FM%3Dweb_SfEventMgtTxSfeventmgtM1%26id%3D0%26moduleToken%3DdummyToken';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsExpectedValueForV76()
    {
        $uid = 100;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version'] = '7.6';
        $actual = $this->viewhelper->render($uid);
        $expected = 'mod.php?M=record_edit&moduleToken=dummyToken&edit%5Btx_sfeventmgt_domain_model_event%5D%5B100' .
            '%5D=edit&returnUrl=index.php%3FM%3Dweb_SfEventMgtTxSfeventmgtM1%26id%3D0%26moduleToken%3DdummyToken';
        $this->assertEquals($expected, $actual);
    }
}
