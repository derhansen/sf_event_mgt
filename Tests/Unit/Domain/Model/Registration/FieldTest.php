<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Utility\FieldType;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class FieldTest extends UnitTestCase
{
    /**
     * Registrationfield object
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->subject = new Field();
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString()
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleSetsTitleField()
    {
        $this->subject->setTitle('A title');
        self::assertAttributeEquals(
            'A title',
            'title',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForString()
    {
        self::assertEquals('', $this->subject->getType());
    }

    /**
     * @test
     */
    public function setTypeSetsTypefield()
    {
        $this->subject->setType('check');
        self::assertAttributeEquals(
            'check',
            'type',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getRequiredReturnsInitialValueForBoolean()
    {
        self::assertFalse($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function setRequiredSetsRequiredField()
    {
        $this->subject->setRequired(true);
        self::assertTrue($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function getPlaceholderReturnsInitialValueForString()
    {
        self::assertEquals('', $this->subject->getPlaceholder());
    }

    /**
     * @test
     */
    public function setPlaceholderSetsPlaceholderField()
    {
        $this->subject->setPlaceholder('placeholder');
        self::assertAttributeEquals(
            'placeholder',
            'placeholder',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDefaultValueReturnsInitialValueForString()
    {
        self::assertEquals('', $this->subject->getDefaultValue());
    }

    /**
     * @test
     */
    public function setDefaultValueSetsDefaultValueField()
    {
        $this->subject->setDefaultValue('default');
        self::assertAttributeEquals(
            'default',
            'defaultValue',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getSettingsReturnsInitialValueForString()
    {
        self::assertEquals('', $this->subject->getSettings());
    }

    /**
     * @test
     */
    public function setSettingsSetsSettingsField()
    {
        $this->subject->setSettings('settings');
        self::assertAttributeEquals(
            'settings',
            'settings',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getEventReturnsInitialValueForEvent()
    {
        self::assertNull($this->subject->getEvent());
    }

    /**
     * @test
     */
    public function setEventSetsEventField()
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertAttributeEquals(
            $event,
            'event',
            $this->subject
        );
    }

    /**
     * Dataprovider for getSettingsForOptionReturnsExpectedValues
     *
     * @return array
     */
    public function getSettingsForOptionDataProvider()
    {
        return [
            'empty string' => [
                '',
                '',
                []
            ],
            'string with one option and no value' => [
                'First option',
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'First option',
                        'selected' => 0
                    ]
                ]
            ],
            'string with two options and no value' => [
                "First option\nSecond option",
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'First option',
                        'selected' => 0
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'Second option',
                        'selected' => 0
                    ]
                ]
            ],
            'string with two options and values' => [
                "First option|value1\nSecond option|value2",
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'value1',
                        'selected' => 0
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'value2',
                        'selected' => 0
                    ]
                ]
            ],
            'string with two options and values, second option default value' => [
                "First option|value1\nSecond option|value2",
                'value2',
                [
                    [
                        'label' => 'First option',
                        'value' => 'value1',
                        'selected' => 0
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'value2',
                        'selected' => 1
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getSettingsForOptionDataProvider
     * @param mixed $settings
     * @param mixed $defaultValue
     * @param mixed $expected
     */
    public function getSettingsForOptionReturnsExpectedValues($settings, $defaultValue, $expected)
    {
        $this->subject->setSettings($settings);
        $this->subject->setDefaultValue($defaultValue);
        self::assertSame($expected, $this->subject->getSettingsForOption());
    }

    /**
     * @test
     */
    public function getValueTypeReturnsInitialValue()
    {
        self::assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    /**
     * DataProvider for getValueTypeReturnsExpectedFieldValues
     */
    public function getValueTypeReturnsExpectedFieldValuesDataProvider()
    {
        return [
            'Input' => [
                FieldType::INPUT,
                FieldValueType::TYPE_TEXT
            ],
            'Checkbox' => [
                FieldType::CHECK,
                FieldValueType::TYPE_ARRAY
            ],
            'Radio' => [
                FieldType::RADIO,
                FieldValueType::TYPE_TEXT
            ],
            'Textarea' => [
                FieldType::TEXTAREA,
                FieldValueType::TYPE_TEXT
            ],
            'Text' => [
                FieldType::TEXT,
                FieldValueType::TYPE_TEXT
            ],
            'Divider' => [
                FieldType::DIVIDER,
                FieldValueType::TYPE_TEXT
            ],
            'Select' => [
                FieldType::SELECT,
                FieldValueType::TYPE_ARRAY
            ],
            'Datetime' => [
                FieldType::DATETIME,
                FieldValueType::TYPE_TEXT
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getValueTypeReturnsExpectedFieldValuesDataProvider
     * @param mixed $fieldType
     * @param mixed $expected
     */
    public function getValueTypeReturnsExpectedFieldValues($fieldType, $expected)
    {
        $this->subject->setType($fieldType);
        self::assertEquals($expected, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function getPartialNameReturnsFieldTypeInUppercase()
    {
        $this->subject->setType('input');
        self::assertEquals('Input', $this->subject->getPartialName());
    }

    /**
     * @return array
     */
    public function getDatepickermodeTypeDataProvider()
    {
        return [
            'datetime-local' => [
                1,
                'datetime-local'
            ],
            'time' => [
                2,
                'time'
            ],
            'date' => [
                0,
                'date'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getDatepickermodeTypeDataProvider
     * @param $datepickerMode
     * @param $expected
     */
    public function getDatepickermodeTypeReturnsExpectedValue($datepickerMode, $expected)
    {
        $this->subject->setDatepickermode($datepickerMode);
        self::assertEquals($expected, $this->subject->getDatepickermodeType());
    }

    /**
     * @test
     */
    public function getTextReturnsInitialValue()
    {
        self::assertEmpty($this->subject->getText());
    }

    /**
     * @test
     */
    public function setTextSetsTextForString()
    {
        $this->subject->setText('TYPO3');
        self::assertEquals('TYPO3', $this->subject->getText());
    }

    /**
     * @test
     */
    public function getDatepickermodeReturnsInitialValue()
    {
        self::assertEquals(0, $this->subject->getDatepickermode());
    }

    /**
     * @test
     */
    public function setDatepickermodeSetsValueForInt()
    {
        $this->subject->setDatepickermode(2);
        self::assertEquals(2, $this->subject->getDatepickermode());
    }
}
