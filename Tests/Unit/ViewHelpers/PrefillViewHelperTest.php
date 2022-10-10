<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;
use stdClass;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefill viewhelper
 */
class PrefillViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewHelperReturnsEmptyStringIfTsfeNotAvailabe()
    {
        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'a field',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsCurrentFieldValueIfValueInParsedBodyAvailable()
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];
        $GLOBALS['TSFE'] = new stdClass();

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn($submittedData);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Torben', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsEmptyStringIfPrefillSettingsEmpty()
    {
        $submittedData = [];
        $GLOBALS['TSFE'] = new stdClass();
        $GLOBALS['TSFE']->fe_user = new stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
        ];

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn($submittedData);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInPrefillSettings()
    {
        $submittedData = [];
        $GLOBALS['TSFE'] = new stdClass();
        $GLOBALS['TSFE']->fe_user = new stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
        ];

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn($submittedData);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInFeUser()
    {
        $GLOBALS['TSFE'] = new stdClass();
        $GLOBALS['TSFE']->fe_user = new stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
        ];

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn([]);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsFieldvalueIfFound()
    {
        $GLOBALS['TSFE'] = new stdClass();
        $GLOBALS['TSFE']->fe_user = new stdClass();
        $GLOBALS['TSFE']->fe_user->user = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn([]);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['lastname' => 'last_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Doe', $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsSubmittedValueIfValidationError()
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects($this->any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects($this->any())->method('getParsedBody')->willReturn($submittedData);
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Torben', $actual);
    }
}
