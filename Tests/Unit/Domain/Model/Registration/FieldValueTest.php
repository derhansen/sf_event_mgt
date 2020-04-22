<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class FieldValueTest extends UnitTestCase
{
    /**
     * Registrationfield object
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new FieldValue();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getValueReturnsInitialValueForString()
    {
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')->willReturn(FieldValueType::TYPE_TEXT);
        $this->subject->setField($mockField);
        self::assertEquals('', $this->subject->getValue());
    }

    /**
     * @test
     */
    public function setValueSetsValueField()
    {
        $this->subject->setValue('A field value');
        self::assertEquals('A field value', $this->subject->getValue());
    }

    /**
     * @test
     */
    public function getValueReturnsArrayForFieldTypeArray()
    {
        $expectedArray = ['value1', 'value2'];
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode($expectedArray));
        self::assertSame($expectedArray, $this->subject->getValue());
    }

    /**
     * @test
     */
    public function getValueReturnsArrayForFieldTypeArrayAndValueString()
    {
        $expectedArray = ['value1'];
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue('value1');
        self::assertSame($expectedArray, $this->subject->getValue());
    }

    /**
     * @test
     */
    public function getFieldReturnsInitialValueForField()
    {
        self::assertNull($this->subject->getField());
    }

    /**
     * @test
     */
    public function setFieldSetsField()
    {
        $field = new Field();
        $this->subject->setField($field);
        self::assertEquals($field, $this->subject->getField());
    }

    /**
     * @test
     */
    public function getRegistrationReturnsInitialValueForRegistration()
    {
        self::assertNull($this->subject->getRegistration());
    }

    /**
     * @test
     */
    public function setRegistrationSetsRegistration()
    {
        $registration = new Registration();
        $this->subject->setRegistration($registration);
        self::assertEquals($registration, $this->subject->getRegistration());
    }

    /**
     * @test
     */
    public function getValueTypeReturnsInitialValueForValueType()
    {
        self::assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function setValueTypeSetsValueType()
    {
        $this->subject->setValueType(FieldValueType::TYPE_ARRAY);
        self::assertEquals(FieldValueType::TYPE_ARRAY, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function getValueForCsvExportReturnsArrayAsCommaSeparatedStringForArrayValues()
    {
        $expectedArray = 'value1,value2';
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode(['value1', 'value2']));
        self::assertSame($expectedArray, $this->subject->getValueForCsvExport());
    }

    /**
     * @test
     */
    public function getValueWhenNoFieldAvailable()
    {
        $this->subject->setValue('Test');
        self::assertSame('Test', $this->subject->getValue());
    }

    /**
     * @test
     */
    public function getValueForCsvExportNoFieldAvailable()
    {
        $this->subject->setValue('Test');
        self::assertSame('Test', $this->subject->getValueForCsvExport());
    }
}
