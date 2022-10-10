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
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefillField viewhelper
 */
class PrefillFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $field = new Field();
        $field->setDefaultValue('Default');

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame('Default', $viewHelper->render());
    }

    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider(): array
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
        $field = $this->getMockBuilder(Field::class)->getMock();
        $field->expects($this->any())->method('getUid')->willReturn($fieldUid);

        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => $fieldValues,
                ],
            ],
        ];

        $originalRequest = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $originalRequest->expects($this->any())->method('getControllerExtensionName')->willReturn('SfEventMgt');
        $originalRequest->expects($this->any())->method('getPluginName')->willReturn('Pievent');
        $originalRequest->expects($this->any())->method('getParsedBody')->willReturn($submittedData);

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects($this->any())->method('getOriginalRequest')->willReturn($originalRequest);

        $renderingContext = $this->getMockBuilder(RenderingContext::class)->disableOriginalConstructor()->getMock();
        $renderingContext->expects($this->any())->method('getRequest')->willReturn($request);

        $viewHelper = new PrefillFieldViewHelper();
        $viewHelper->setRenderingContext($renderingContext);
        $viewHelper->setArguments(['registrationField' => $field]);

        self::assertSame($expected, $viewHelper->render());
    }
}
