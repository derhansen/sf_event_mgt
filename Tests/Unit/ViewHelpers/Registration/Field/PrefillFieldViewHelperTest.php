<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefillField viewhelper
 */
class PrefillFieldViewHelperTest extends UnitTestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $field = new Field();
        $field->setDefaultValue('Default');

        $request = $this->prophesize(Request::class);
        $renderingContext = $this->prophesize(RenderingContext::class);
        $renderingContext->getVariableProvider()->willReturn(null);
        $renderingContext->getViewHelperVariableContainer()->willReturn(null);
        $renderingContext->getRequest()->willReturn($request->reveal());

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame('Default', $viewHelper->render());
    }

    /**
     * @return array
     */
    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider()
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

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @param mixed $fieldUid
     * @param mixed $fieldValues
     * @param mixed $expected
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist($fieldUid, $fieldValues, $expected)
    {
        $field = $this->prophesize(Field::class);
        $field->getUid()->willReturn($fieldUid);

        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => $fieldValues,
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

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext->reveal());
        $viewHelper->setArguments(['registrationField' => $field->reveal()]);

        self::assertSame($expected, $viewHelper->render());
    }
}
