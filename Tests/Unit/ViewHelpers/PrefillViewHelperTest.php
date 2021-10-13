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
use Prophecy\PhpUnit\ProphecyTrait;
use stdClass;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefill viewhelper
 */
class PrefillViewHelperTest extends UnitTestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function viewHelperReturnsEmptyStringIfTsfeNotAvailabe()
    {
        $request = $this->prophesize(Request::class);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments([
            'fieldname' => 'a field',
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn($submittedData);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn($submittedData);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn($submittedData);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn([]);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn([]);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
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

        $request = $this->prophesize(Request::class);
        $request->getControllerExtensionName()->willReturn('SfEventMgt');
        $request->getPluginName()->willReturn('Pieventregistration');
        $request->getParsedBody()->willReturn($submittedData);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Torben', $actual);
    }
}
