<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new Field();
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
    public function getTitleReturnsInitialValueForString()
    {
        $this->assertEquals('', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleSetsTitleField()
    {
        $this->subject->setTitle('A title');
        $this->assertAttributeEquals(
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
        $this->assertEquals('', $this->subject->getType());
    }

    /**
     * @test
     */
    public function setTypeSetsTypefield()
    {
        $this->subject->setType('check');
        $this->assertAttributeEquals(
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
        $this->assertFalse($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function setRequiredSetsRequiredField()
    {
        $this->subject->setRequired(true);
        $this->assertTrue($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function getPlaceholderReturnsInitialValueForString()
    {
        $this->assertEquals('', $this->subject->getPlaceholder());
    }

    /**
     * @test
     */
    public function setPlaceholderSetsPlaceholderField()
    {
        $this->subject->setPlaceholder('placeholder');
        $this->assertAttributeEquals(
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
        $this->assertEquals('', $this->subject->getDefaultValue());
    }

    /**
     * @test
     */
    public function setDefaultValueSetsDefaultValueField()
    {
        $this->subject->setDefaultValue('default');
        $this->assertAttributeEquals(
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
        $this->assertEquals('', $this->subject->getSettings());
    }

    /**
     * @test
     */
    public function setSettingsSetsSettingsField()
    {
        $this->subject->setSettings('settings');
        $this->assertAttributeEquals(
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
        $this->assertNull($this->subject->getEvent());
    }

    /**
     * @test
     */
    public function setEventSetsEventField()
    {
        $event = new Event();
        $this->subject->setEvent($event);
        $this->assertAttributeEquals(
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
        $this->assertSame($expected, $this->subject->getSettingsForOption());
    }

    /**
     * @test
     */
    public function getValueTypeReturnsInitialValue()
    {
        $this->assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    /**
     * DataProvider for getValueTypeReturnsExpectedFieldValues
     */
    public function getValueTypeReturnsExpectedFieldValuesDataProvider()
    {
        return [
            'Type Text' => [
                FieldType::INPUT,
                FieldValueType::TYPE_TEXT
            ],
            'Type Check' => [
                FieldType::CHECK,
                FieldValueType::TYPE_ARRAY
            ],
            'Type Radio' => [
                FieldType::RADIO,
                FieldValueType::TYPE_TEXT
            ],
            'Type Textarea' => [
                FieldType::TEXTAREA,
                FieldValueType::TYPE_TEXT
            ],
            'Type Text' => [
                FieldType::TEXT,
                FieldValueType::TYPE_TEXT
            ],
            'Type Divider' => [
                FieldType::DIVIDER,
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
        $this->assertEquals($expected, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function getPartialNameReturnsFieldTypeInUppercase()
    {
        $this->subject->setType('input');
        $this->assertEquals('Input', $this->subject->getPartialName());
    }
}
