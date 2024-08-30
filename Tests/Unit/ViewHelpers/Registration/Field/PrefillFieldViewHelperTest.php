<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Registration\Field;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PrefillFieldViewHelperTest extends UnitTestCase
{
    #[Test]
    public function viewHelperReturnsFieldDefaultValue(): void
    {
        $field = new Field();
        $field->setDefaultValue('Default');

        $extbaseRequestParameters = $this->createMock(ExtbaseRequestParameters::class);
        $request = $this->createMock(Request::class);
        $request->expects(self::once())->method('getAttribute')->with('extbase')->willReturn($extbaseRequestParameters);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame('Default', $viewHelper->render());
    }

    #[Test]
    public function viewHelperReturnsFieldFeUserValue(): void
    {
        $field = new Field();
        $field->setFeuserValue('first_name');

        $mockFeUser = $this->createMock(FrontendUserAuthentication::class);
        $mockFeUser->user = [
            'first_name' => 'John',
        ];

        $mockTsfe = $this->createMock(TypoScriptFrontendController::class);
        $mockTsfe->fe_user = $mockFeUser;
        $GLOBALS['TSFE'] = $mockTsfe;

        $extbaseRequestParameters = $this->createMock(ExtbaseRequestParameters::class);
        $request = $this->createMock(Request::class);
        $request->expects(self::once())->method('getAttribute')->with('extbase')->willReturn($extbaseRequestParameters);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame('John', $viewHelper->render());
    }

    public static function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider(): array
    {
        return [
            'submitted value returned' => [
                1,
                [
                    '1' => 'Submitted value',
                ],
                'Submitted value',
            ],
            'empty value returned if not found' => [
                2,
                [
                    '1' => 'Submitted value',
                ],
                '',
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist(
        int $fieldUid,
        array $fieldValues,
        string $expected
    ): void {
        $field = $this->createMock(Field::class);
        $field->expects(self::any())->method('getUid')->willReturn($fieldUid);

        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => $fieldValues,
                ],
            ],
        ];

        $originalRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $originalRequest->expects(self::any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $originalRequest->expects(self::any())->method('getPluginName')->willReturn('Pievent');
        $originalRequest->expects(self::any())->method('getParsedBody')->willReturn($submittedData);

        $extbaseRequestParameters = $this->createMock(ExtbaseRequestParameters::class);
        $extbaseRequestParameters->expects(self::once())->method('getOriginalRequest')->willReturn($originalRequest);
        $request = $this->createMock(Request::class);
        $request->expects(self::once())->method('getAttribute')->with('extbase')->willReturn($extbaseRequestParameters);
        $renderingContext = $this->createMock(RenderingContext::class);
        $renderingContext->expects(self::any())->method('getRequest')->willReturn($request);

        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects(self::any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame($expected, $viewHelper->render());
    }
}
