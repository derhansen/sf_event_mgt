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
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsCurrentFieldValueIfValueInGPAvailable()
    {
        $_GET = [
            'tx_sfeventmgt_pievent' => [
                'registration' => ['fieldname' => 'Existing Value']
            ]
        ];
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'fieldname'
        ]);
        $actual = $viewHelper->render();
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
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfPrefillSettingsEmpty()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfFieldNotFoundInPrefillSettings()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['firstname' => 'first_name']
        ]);
        $actual = $viewHelper->render();
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsEmptyStringIfFieldNotFoundInFeUser()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John'
        ];

        $arguments = [
            'fieldname' => 'lastname',
            'prefillSettings' => ['lastname' => 'last_name']
        ];

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));
        $actual = $viewHelper->_call('render');
        $this->assertSame('', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsFieldvalueIfFound()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $arguments = [
            'fieldname' => 'lastname',
            'prefillSettings' => ['lastname' => 'last_name']
        ];

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));
        $actual = $viewHelper->_call('render');
        $this->assertSame('Doe', $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewReturnsSubmittedValueIfValidationError()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $GLOBALS['TSFE']->fe_user = new \stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $arguments = [
            'fieldname' => 'lastname',
            'prefillSettings' => ['lastname' => 'last_name']
        ];

        $requestArguments = [
            'registration' => [
                'lastname' => 'Submitted Lastname'
            ]
        ];

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects($this->once())->method('getArguments')
            ->will($this->returnValue($requestArguments));

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));
        $actual = $viewHelper->_call('render');
        $this->assertSame('Submitted Lastname', $actual);
    }
}
