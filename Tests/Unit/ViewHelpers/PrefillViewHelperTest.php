<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

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

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;

/**
 * Test case for prefill viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillViewHelperTest extends UnitTestCase
{

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfTsfeNotAvailabe()
    {
        $viewHelper = new PrefillViewHelper();
        $actual = $viewHelper->render('a field', []);
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsCurrentFieldValueIfValueInGPAvailable()
    {
        \TYPO3\CMS\Core\Utility\GeneralUtility::_GETset([
                'tx_sfeventmgt_pievent' => [
                    'registration' => ['fieldname' => 'Existing Value']
                ]
            ]
        );
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $actual = $viewHelper->render('fieldname', []);
        $this->assertSame('Existing Value', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfNoTsfeLoginuserNotAvailabe()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $actual = $viewHelper->render('a field', []);
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfPrefillSettingsEmpty()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->loginUser = 1;
        $viewHelper = new PrefillViewHelper();
        $actual = $viewHelper->render('a field', []);
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfFieldNotFoundInPrefillSettings()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->loginUser = 1;
        $viewHelper = new PrefillViewHelper();
        $actual = $viewHelper->render('lastname', ['firstname' => 'first_name']);
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfFieldNotFoundInFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->loginUser = 1;
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John'
        ];

        $mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
            ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
            ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
            ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);
        $actual = $viewHelper->render('firstname', ['firstname' => 'unknown_field']);
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsFieldvalueIfFound()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->loginUser = 1;
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];
        $mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
            ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
            ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
            ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);
        $actual = $viewHelper->render('lastname', ['lastname' => 'last_name']);
        $this->assertSame('Doe', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsSubmittedValueIfValidationError()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->loginUser = 1;
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $arguments = [
            'registration' => [
                'lastname' => 'Submitted Lastname'
            ]
        ];

        $mockOriginalRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
            ['getArguments'], [], '', false);
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Request',
            ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue($mockOriginalRequest));

        $mockControllerContext = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\ControllerContext',
            ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock('DERHANSEN\\SfEventMgt\\ViewHelpers\\PrefillViewHelper',
            ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);
        $actual = $viewHelper->render('lastname', ['lastname' => 'last_name']);
        $this->assertSame('Submitted Lastname', $actual);
    }
}