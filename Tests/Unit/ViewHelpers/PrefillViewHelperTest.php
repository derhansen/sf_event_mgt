<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefill viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewReturnsEmptyStringIfTsfeNotAvailabe()
    {
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
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
        self::assertSame('Existing Value', $actual);
    }

    /**
     * @test
     */
    public function viewReturnsEmptyStringIfNoTsfeLoginuserNotAvailabe()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function viewReturnsEmptyStringIfPrefillSettingsEmpty()
    {
        $GLOBALS['TSFE'] = new \stdClass();
        $viewHelper = new PrefillViewHelper();
        $viewHelper->setArguments([
            'fieldname' => 'a field'
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
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
        self::assertSame('', $actual);
    }

    /**
     * @test
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

        $request = $this->prophesize(Request::class);
        $request->getOriginalRequest()->willReturn(null);

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($request->reveal());
        $actual = $viewHelper->_call('render');
        self::assertSame('', $actual);
    }

    /**
     * @test
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

        $request = $this->prophesize(Request::class);
        $request->getOriginalRequest()->willReturn(null);

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($request->reveal());
        $actual = $viewHelper->_call('render');
        self::assertSame('Doe', $actual);
    }

    /**
     * @test
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

        $originalRequest = $this->prophesize(Request::class);
        $originalRequest->getArguments()->willReturn($requestArguments);

        $request = $this->prophesize(Request::class);
        $request->getOriginalRequest()->willReturn($originalRequest->reveal());

        $viewHelper = $this->getAccessibleMock(PrefillViewHelper::class, ['getRequest']);
        $viewHelper->_set('arguments', $arguments);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($request->reveal());
        $actual = $viewHelper->_call('render');
        self::assertSame('Submitted Lastname', $actual);
    }
}
