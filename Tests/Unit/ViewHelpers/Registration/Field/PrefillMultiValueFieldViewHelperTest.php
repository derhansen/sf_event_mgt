<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillMultiValueFieldViewHelper;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefillMultiValueField viewhelper
 */
class PrefillMultiValueFieldViewHelperTest extends UnitTestCase
{
    use ProphecyTrait;

    public function viewHelperReturnsExpectedResultIfNoOriginalRequestDataProvider(): array
    {
        return [
            'Default value selected' => [
                'Default',
                'Default',
                true,
            ],
            'Default value not selected' => [
                'Default',
                'Foo',
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsExpectedResultIfNoOriginalRequestDataProvider
     */
    public function viewHelperReturnsExpectedResultIfNoOriginalRequest($defaultValue, $currentValue, $expected)
    {
        $field = new Field();
        $field->setDefaultValue($defaultValue);

        $request = $this->prophesize(Request::class);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillMultiValueFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments(['registrationField' => $field, 'currentValue' => $currentValue]);

        self::assertEquals($expected, $viewHelper->render());
    }

    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider(): array
    {
        return [
            'submitted value is field value for string' => [
                1,
                1,
                'option1',
                'option1',
                true,
            ],
            'submitted value is field value for array' => [
                1,
                1,
                'option1',
                ['option1', 'option2'],
                true,
            ],
            'submitted value is not field value for array' => [
                1,
                1,
                'option3',
                ['option1', 'option2'],
                false,
            ],
            'submitted registration field uid is not registration field uid' => [
                1,
                2,
                'option1',
                ['option1', 'option2'],
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @param mixed $submittedRegistrationFieldUid
     * @param mixed $registrationFieldUid
     * @param mixed $currentValue
     * @param mixed $fieldValue
     * @param mixed $expected
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist(
        $submittedRegistrationFieldUid,
        $registrationFieldUid,
        $currentValue,
        $fieldValue,
        $expected
    ) {
        $field = $this->prophesize(Field::class);
        $field->getUid()->willReturn($registrationFieldUid);

        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => [
                        $submittedRegistrationFieldUid => $fieldValue,
                    ],
                ],
            ],
        ];

        $originalRequest = $this->prophesize(Request::class);
        $originalRequest->getControllerExtensionName()->willReturn('SfEventMgt');
        $originalRequest->getPluginName()->willReturn('Pievent');
        $originalRequest->getParsedBody()->willReturn($submittedData);
        $request = $this->prophesize(Request::class);
        $request->getOriginalRequest()->willReturn($originalRequest->reveal());
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillMultiValueFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments(['registrationField' => $field->reveal(), 'currentValue' => $currentValue]);

        self::assertEquals($expected, $viewHelper->render());
    }

    /**
     * @test
     */
    public function viewHelperReturnsFalseIfOriginalRequestHasNoRegistrationFieldValues()
    {
        $field = $this->prophesize(Field::class);
        $field->getUid()->willReturn(1);

        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => [],
                ],
            ],
        ];

        $originalRequest = $this->prophesize(Request::class);
        $originalRequest->getControllerExtensionName()->willReturn('SfEventMgt');
        $originalRequest->getPluginName()->willReturn('Pievent');
        $originalRequest->getParsedBody()->willReturn($submittedData);
        $request = $this->prophesize(Request::class);
        $request->getOriginalRequest()->willReturn($originalRequest->reveal());
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillMultiValueFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments(['registrationField' => $field->reveal(), 'currentValue' => 'foo']);

        self::assertFalse($viewHelper->render());
    }
}
