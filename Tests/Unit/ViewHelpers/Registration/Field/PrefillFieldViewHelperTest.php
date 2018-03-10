<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Request;

/**
 * Test case for prefillField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $mockRequest = $this->getMock(Request::class, ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $mockControllerContext = $this->getMock(ControllerContext::class, ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);

        $field = new Field();
        $field->setDefaultValue('Default');
        $actual = $viewHelper->render($field);
        $this->assertSame('Default', $actual);
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
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                'Submitted value'
            ],
            'empty value returned if not found' => [
                2,
                [
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                ''
            ]
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @param mixed $fieldUid
     * @param mixed $fieldValues
     * @param mixed $expected
     * @return void
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist($fieldUid, $fieldValues, $expected)
    {
        $arguments = [
            'registration' => [
                'fieldValues' => [$fieldValues]
            ]
        ];

        $mockOriginalRequest = $this->getMock(Request::class, ['getArguments'], [], '', false);
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMock(Request::class, ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $mockControllerContext = $this->getMock(ControllerContext::class, ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);

        $mockField = $this->getMock(Field::class, [], [], '', false);
        $mockField->expects($this->once())->method('getUid')->will($this->returnValue($fieldUid));

        $actual = $viewHelper->render($mockField);
        $this->assertSame($expected, $actual);
    }
}
