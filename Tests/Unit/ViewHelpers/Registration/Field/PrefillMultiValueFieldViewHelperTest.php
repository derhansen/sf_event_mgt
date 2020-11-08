<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillMultiValueFieldViewHelper;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Property\PropertyMapper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefillMultiValueField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillMultiValueFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('getOriginalRequest')->willReturn(null);

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($mockRequest);

        $field = new Field();
        $field->setDefaultValue('Default');
        $currentValue = 'Default';

        $viewHelper->_set('arguments', ['registrationField' => $field, 'currentValue' => $currentValue]);
        $actual = $viewHelper->_callRef('render');
        self::assertTrue($actual);
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
        $mockField->expects(self::any())->method('getUid')->willReturn($submittedRegistrationFieldUid);

        $mockFieldValue = $this->getMockBuilder(FieldValue::class)
            ->setMethods(['getField', 'getValue'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFieldValue->expects(self::any())->method('getField')->willReturn($mockField);
        $mockFieldValue->expects(self::any())->method('getValue')->willReturn($fieldValue);

        $mockPropertyMapper = $this->getMockBuilder(PropertyMapper::class)
            ->setMethods(['convert'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockPropertyMapper->expects(self::any())->method('convert')->willReturn($mockFieldValue);

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects(self::once())->method('getArguments')->willReturn($arguments);

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('getOriginalRequest')
            ->willReturn($mockOriginalRequest);

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($mockRequest);
        $viewHelper->_set('propertyMapper', $mockPropertyMapper);

        $mockSubmittedField = $this->getMockBuilder(Field::class)->getMock();
        $mockSubmittedField->expects(self::once())->method('getUid')->willReturn($registrationFieldUid);

        $viewHelper->_set('arguments', ['registrationField' => $mockSubmittedField, 'currentValue' => $currentValue]);
        $actual = $viewHelper->_callRef('render');
        self::assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function viewHelperReturnsFalseIfOriginalRequestHasNoRegistrationfieldValues()
    {
        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects(self::once())->method('getArguments')->willReturn(null);

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('getOriginalRequest')
            ->willReturn($mockOriginalRequest);

        $viewHelper = $this->getAccessibleMock(PrefillMultiValueFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($mockRequest);

        $mockSubmittedField = $this->getMockBuilder(Field::class)->getMock();

        $viewHelper->_set('arguments', ['registrationField' => $mockSubmittedField, 'currentValue' => null]);
        $actual = $viewHelper->_callRef('render');
        self::assertFalse($actual);
    }
}
