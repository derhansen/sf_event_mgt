<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new FieldValue();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getValueReturnsInitialValueForString()
    {
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects($this->once())->method('getValueType')->will($this->returnValue(FieldValueType::TYPE_TEXT));
        $this->subject->setField($mockField);
        $this->assertEquals('', $this->subject->getValue());
    }

    /**
     * @test
     */
    public function setValueSetsValueField()
    {
        $this->subject->setValue('A field value');
        $this->assertAttributeEquals(
            'A field value',
            'value',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getValueReturnsArrayForFieldTypeArray()
    {
        $expectedArray = ['value1', 'value2'];
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects($this->once())->method('getValueType')
            ->will($this->returnValue(FieldValueType::TYPE_ARRAY));
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode($expectedArray));
        $this->assertSame($expectedArray, $this->subject->getValue());
    }

    /**
     * @test
     */
    public function getFieldReturnsInitialValueForField()
    {
        $this->assertNull($this->subject->getField());
    }

    /**
     * @test
     */
    public function setFieldSetsField()
    {
        $field = new Field();
        $this->subject->setField($field);
        $this->assertAttributeEquals(
            $field,
            'field',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRegistrationReturnsInitialValueForRegistration()
    {
        $this->assertNull($this->subject->getRegistration());
    }

    /**
     * @test
     */
    public function setRegistrationSetsRegistration()
    {
        $registration = new Registration();
        $this->subject->setRegistration($registration);
        $this->assertAttributeEquals(
            $registration,
            'registration',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getValueTypeReturnsInitialValueForValueType()
    {
        $this->assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function setValueTypeSetsValueType()
    {
        $this->subject->setValueType(FieldValueType::TYPE_ARRAY);
        $this->assertEquals(FieldValueType::TYPE_ARRAY, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function getValueForCsvExportReturnsArrayAsCommaSeparatedStringForArrayValues()
    {
        $expectedArray = 'value1,value2';
        $mockField = $this->getMockBuilder(Field::class)->setMethods(['getValueType'])->getMock();
        $mockField->expects($this->once())->method('getValueType')
            ->will($this->returnValue(FieldValueType::TYPE_ARRAY));
        $this->subject->setField($mockField);
        $this->subject->setValue(json_encode(['value1', 'value2']));
        $this->assertSame($expectedArray, $this->subject->getValueForCsvExport());
    }
}
