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
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Property\PropertyMapper;

/**
 * Test case for prefillMultiValueField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillMultiValueFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')->will($this->returnValue(null));

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $field = new Field();
        $field->setDefaultValue('Default');
        $currentValue = 'Default';

        $viewHelper->_set('arguments', ['registrationField' => $field, 'currentValue' => $currentValue]);
        $actual = $viewHelper->_callRef('render');
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
     * @param mixed $submittedRegistrationFieldUid
     * @param mixed $registrationFieldUid
     * @param mixed $currentValue
     * @param mixed $fieldValue
     * @param mixed $expected
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

        $mockPropertyMapper = $this->getMockBuilder(PropertyMapper::class)
            ->setMethods(['convert'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockPropertyMapper->expects($this->any())->method('convert')->will($this->returnValue($mockFieldValue));

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue($arguments));

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));
        $viewHelper->_set('propertyMapper', $mockPropertyMapper);

        $mockSubmittedField = $this->getMockBuilder(Field::class)->getMock();
        $mockSubmittedField->expects($this->once())->method('getUid')->will($this->returnValue($registrationFieldUid));

        $viewHelper->_set('arguments', ['registrationField' => $mockSubmittedField, 'currentValue' => $currentValue]);
        $actual = $viewHelper->_callRef('render');
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     * @return void
     */
    public function viewHelperReturnsFalseIfOriginalRequestHasNoRegistrationfieldValues()
    {
        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects($this->once())->method('getArguments')->will($this->returnValue(null));

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('getOriginalRequest')
            ->will($this->returnValue($mockOriginalRequest));

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects($this->once())->method('getRequest')->will($this->returnValue($mockRequest));

        $mockSubmittedField = $this->getMockBuilder(Field::class)->getMock();

        $viewHelper->_set('arguments', ['registrationField' => $mockSubmittedField, 'currentValue' => null]);
        $actual = $viewHelper->_callRef('render');
        $this->assertFalse($actual);
    }
}
