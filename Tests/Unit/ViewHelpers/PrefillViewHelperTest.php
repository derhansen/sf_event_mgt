<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\ViewHelpers\PrefillViewHelper;
use stdClass;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

use function PHPUnit\Framework\any;

/**
 * Test case for prefill viewHelper
 */
class PrefillViewHelperTest extends UnitTestCase
{
    #[Test]
    public function viewHelperReturnsEmptyStringIfFrontendUserNotAvailable(): void
    {
        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'a field',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    #[Test]
    public function viewHelperReturnsCurrentFieldValueIfValueInParsedBodyAvailable(): void
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];

        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getParsedBody')->willReturn($submittedData);
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Torben', $actual);
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfPrefillSettingsEmpty(): void
    {
        $submittedData = [];

        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getParsedBody')->willReturn($submittedData);
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'prefillSettings' => [],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInPrefillSettings(): void
    {
        $submittedData = [];
        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getParsedBody')->willReturn($submittedData);
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInFeUser(): void
    {
        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['firstname' => 'first_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('', $actual);
    }

    #[Test]
    public function viewHelperReturnsFieldvalueIfFound(): void
    {
        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $frontendUser->user = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

        $viewHelper = new PrefillViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments([
            'fieldname' => 'lastname',
            'prefillSettings' => ['lastname' => 'last_name'],
        ]);
        $actual = $viewHelper->render();
        self::assertSame('Doe', $actual);
    }

    #[Test]
    public function viewHelperReturnsSubmittedValueIfValidationError(): void
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];

        $frontendUser = $this->createMock(FrontendUserAuthentication::class);
        $frontendUser->user = [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ];
        $request = $this->createMock(Request::class);
        $request->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::any())->method('getPluginName')->willReturn('Pieventregistration');
        $request->expects(self::any())->method('getParsedBody')->willReturn($submittedData);
        $request->expects(self::any())->method('getAttribute')->with('frontend.user')->willReturn($frontendUser);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getAttribute')->willReturn($request);

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
