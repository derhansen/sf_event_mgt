<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Request;

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
        GeneralUtility::_GETset(
            [
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

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $mockControllerContext = $this->getMockBuilder(ControllerContext::class)
            ->setMethods(['getRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest)
        );

        $viewHelper = $this->getAccessibleMock(
            PrefillViewHelper::class,
            ['dummy'],
            [],
            '',
            false
        );
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
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $mockControllerContext = $this->getMockBuilder(ControllerContext::class)
            ->setMethods(['getRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest)
        );

        $viewHelper = $this->getAccessibleMock(
            PrefillViewHelper::class,
            ['dummy'],
            [],
            '',
            false
        );
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

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue($mockOriginalRequest));

        $mockControllerContext = $this->getMockBuilder(ControllerContext::class)
            ->setMethods(['getRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockControllerContext->expects($this->once())->method('getRequest')->will(
            $this->returnValue($mockRequest)
        );

        $viewHelper = $this->getAccessibleMock(
            PrefillViewHelper::class,
            ['dummy'],
            [],
            '',
            false
        );
        $viewHelper->_set('controllerContext', $mockControllerContext);
        $actual = $viewHelper->render('lastname', ['lastname' => 'last_name']);
        $this->assertSame('Submitted Lastname', $actual);
    }
}
