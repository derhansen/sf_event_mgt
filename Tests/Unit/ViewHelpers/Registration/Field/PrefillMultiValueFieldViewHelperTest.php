<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillMultiValueFieldViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Property\PropertyMapper;

/**
 * Test case for prefillMultiValueField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillMultiValueFieldTest extends UnitTestCase
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

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);

        $field = new Field();
        $field->setDefaultValue('Default');
        $currentValue = 'Default';
        $actual = $viewHelper->render($field, $currentValue);
        $this->assertTrue($actual);
    }

    /**
     * @return array
     */
    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider()
    {
        return [
            'submitted value is field value for string' => [
                1,
                1,
                'option1',
                'option1',
                true
            ],
            'submitted value is field value for array' => [
                1,
                1,
                'option1',
                ['option1', 'option2'],
                true
            ],
            'submitted value is not field value for array' => [
                1,
                1,
                'option3',
                ['option1', 'option2'],
                false
            ],
            'submitted registration field uid is not registration field uid' => [
                1,
                2,
                'option1',
                ['option1', 'option2'],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @return void
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist(
        $submittedRegistrationFieldUid,
        $registrationFieldUid,
        $currentValue,
        $fieldValue,
        $expected
    ) {
        $arguments = [
            'registration' => [
                'fieldValues' => [
                    ['dummy' => 'value']
                ]
            ]
        ];

        $mockField = $this->getMockBuilder(Field::class)
            ->setMethods(['getUid'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockField->expects($this->any())->method('getUid')->will($this->returnValue($submittedRegistrationFieldUid));

        $mockFieldValue = $this->getMockBuilder(FieldValue::class)
            ->setMethods(['getField', 'getValue'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFieldValue->expects($this->any())->method('getField')->will($this->returnValue($mockField));
        $mockFieldValue->expects($this->any())->method('getValue')->will($this->returnValue($fieldValue));

        $mockPropertyMapper =  $this->getMockBuilder(PropertyMapper::class)
            ->setMethods(['convert'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockPropertyMapper->expects($this->any())->method('convert')->will($this->returnValue($mockFieldValue));

        $mockOriginalRequest = $this->getMock(Request::class, ['getArguments'], [], '', false);
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMock(Request::class, ['getOriginalRequest'], [], '', false);
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $mockControllerContext = $this->getMock(ControllerContext::class, ['getRequest'], [], '', false);
        $mockControllerContext->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['dummy'], [], '', false);
        $viewHelper->_set('controllerContext', $mockControllerContext);
        $viewHelper->_set('propertyMapper', $mockPropertyMapper);

        $mockSubmittedField = $this->getMock(Field::class, [], [], '', false);
        $mockSubmittedField->expects($this->once())->method('getUid')->will($this->returnValue($registrationFieldUid));

        $actual = $viewHelper->render($mockSubmittedField, $currentValue);
        $this->assertSame($expected, $actual);
    }
}

