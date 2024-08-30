<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Registration;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FieldValueTest extends UnitTestCase
{
    protected FieldValue $subject;

    protected function setUp(): void
    {
        $this->subject = new FieldValue();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getValueReturnsInitialValueForString(): void
    {
        $mockField = $this->getMockBuilder(Field::class)->onlyMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')->willReturn(FieldValueType::TYPE_TEXT);
        $this->subject->setField($mockField);
        self::assertEquals('', $this->subject->getValue());
    }

    #[Test]
    public function setValueSetsValueField(): void
    {
        $this->subject->setValue('A field value');
        self::assertEquals('A field value', $this->subject->getValue());
    }

    #[Test]
    public function getValueReturnsArrayForFieldTypeArray(): void
    {
        $expectedArray = ['value1', 'value2'];
        $mockField = $this->getMockBuilder(Field::class)->onlyMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode($expectedArray));
        self::assertSame($expectedArray, $this->subject->getValue());
    }

    #[Test]
    public function getValueReturnsArrayForFieldTypeArrayAndValueString(): void
    {
        $expectedArray = ['value1'];
        $mockField = $this->getMockBuilder(Field::class)->onlyMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue('value1');
        self::assertSame($expectedArray, $this->subject->getValue());
    }

    #[Test]
    public function getFieldReturnsInitialValueForField(): void
    {
        self::assertNull($this->subject->getField());
    }

    #[Test]
    public function setFieldSetsField(): void
    {
        $field = new Field();
        $this->subject->setField($field);
        self::assertEquals($field, $this->subject->getField());
    }

    #[Test]
    public function getRegistrationReturnsInitialValueForRegistration(): void
    {
        self::assertNull($this->subject->getRegistration());
    }

    #[Test]
    public function setRegistrationSetsRegistration(): void
    {
        $registration = new Registration();
        $this->subject->setRegistration($registration);
        self::assertEquals($registration, $this->subject->getRegistration());
    }

    #[Test]
    public function getValueTypeReturnsInitialValueForValueType(): void
    {
        self::assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    #[Test]
    public function setValueTypeSetsValueType(): void
    {
        $this->subject->setValueType(FieldValueType::TYPE_ARRAY);
        self::assertEquals(FieldValueType::TYPE_ARRAY, $this->subject->getValueType());
    }

    #[Test]
    public function getValueForCsvExportReturnsArrayAsCommaSeparatedStringForArrayValues(): void
    {
        $expectedArray = 'value1,value2';
        $mockField = $this->getMockBuilder(Field::class)->onlyMethods(['getValueType'])->getMock();
        $mockField->expects(self::once())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode(['value1', 'value2']));
        self::assertSame($expectedArray, $this->subject->getValueForCsvExport());
    }

    #[Test]
    public function getValueWhenNoFieldAvailable(): void
    {
        $this->subject->setValue('Test');
        self::assertSame('Test', $this->subject->getValue());
    }

    #[Test]
    public function getValueForCsvExportNoFieldAvailable(): void
    {
        $this->subject->setValue('Test');
        self::assertSame('Test', $this->subject->getValueForCsvExport());
    }
}
