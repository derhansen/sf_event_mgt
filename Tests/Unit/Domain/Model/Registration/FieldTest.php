<?php

declare(strict_types=1);

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
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FieldTest extends UnitTestCase
{
    protected Field $subject;

    protected function setUp(): void
    {
        $this->subject = new Field();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function setTitleSetsTitleField(): void
    {
        $this->subject->setTitle('A title');
        self::assertEquals('A title', $this->subject->getTitle());
    }

    /**
     * @test
     */
    public function getTypeReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getType());
    }

    /**
     * @test
     */
    public function setTypeSetsTypefield(): void
    {
        $this->subject->setType('check');
        self::assertEquals('check', $this->subject->getType());
    }

    /**
     * @test
     */
    public function getRequiredReturnsInitialValueForBoolean(): void
    {
        self::assertFalse($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function setRequiredSetsRequiredField(): void
    {
        $this->subject->setRequired(true);
        self::assertTrue($this->subject->getRequired());
    }

    /**
     * @test
     */
    public function getPlaceholderReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getPlaceholder());
    }

    /**
     * @test
     */
    public function setPlaceholderSetsPlaceholderField(): void
    {
        $this->subject->setPlaceholder('placeholder');
        self::assertEquals('placeholder', $this->subject->getPlaceholder());
    }

    /**
     * @test
     */
    public function getDefaultValueReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getDefaultValue());
    }

    /**
     * @test
     */
    public function setDefaultValueSetsDefaultValueField(): void
    {
        $this->subject->setDefaultValue('default');
        self::assertEquals('default', $this->subject->getDefaultValue());
    }

    /**
     * @test
     */
    public function getSettingsReturnsInitialValueForString(): void
    {
        self::assertEquals('', $this->subject->getSettings());
    }

    /**
     * @test
     */
    public function setSettingsSetsSettingsField(): void
    {
        $this->subject->setSettings('settings');
        self::assertEquals('settings', $this->subject->getSettings());
    }

    /**
     * @test
     */
    public function getEventReturnsInitialValueForEvent(): void
    {
        self::assertNull($this->subject->getEvent());
    }

    /**
     * @test
     */
    public function setEventSetsEventField(): void
    {
        $event = new Event();
        $this->subject->setEvent($event);
        self::assertEquals($event, $this->subject->getEvent());
    }

    public static function getSettingsForOptionDataProvider(): array
    {
        return [
            'empty string' => [
                '',
                '',
                [],
            ],
            'string with one option and no value' => [
                'First option',
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'First option',
                        'selected' => 0,
                    ],
                ],
            ],
            'string with two options and no value' => [
                "First option\nSecond option",
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'First option',
                        'selected' => 0,
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'Second option',
                        'selected' => 0,
                    ],
                ],
            ],
            'string with two options and values' => [
                "First option|value1\nSecond option|value2",
                '',
                [
                    [
                        'label' => 'First option',
                        'value' => 'value1',
                        'selected' => 0,
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'value2',
                        'selected' => 0,
                    ],
                ],
            ],
            'string with two options and values, second option default value' => [
                "First option|value1\nSecond option|value2",
                'value2',
                [
                    [
                        'label' => 'First option',
                        'value' => 'value1',
                        'selected' => 0,
                    ],
                    [
                        'label' => 'Second option',
                        'value' => 'value2',
                        'selected' => 1,
                    ],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getSettingsForOptionDataProvider
     */
    public function getSettingsForOptionReturnsExpectedValues(string $settings, string $defaultValue, array $expected): void
    {
        $this->subject->setSettings($settings);
        $this->subject->setDefaultValue($defaultValue);
        self::assertSame($expected, $this->subject->getSettingsForOption());
    }

    /**
     * @test
     */
    public function getValueTypeReturnsInitialValue(): void
    {
        self::assertEquals(FieldValueType::TYPE_TEXT, $this->subject->getValueType());
    }

    public static function getValueTypeReturnsExpectedFieldValuesDataProvider(): array
    {
        return [
            'Input' => [
                FieldType::INPUT,
                FieldValueType::TYPE_TEXT,
            ],
            'Checkbox' => [
                FieldType::CHECK,
                FieldValueType::TYPE_ARRAY,
            ],
            'Radio' => [
                FieldType::RADIO,
                FieldValueType::TYPE_TEXT,
            ],
            'Textarea' => [
                FieldType::TEXTAREA,
                FieldValueType::TYPE_TEXT,
            ],
            'Text' => [
                FieldType::TEXT,
                FieldValueType::TYPE_TEXT,
            ],
            'Divider' => [
                FieldType::DIVIDER,
                FieldValueType::TYPE_TEXT,
            ],
            'Select' => [
                FieldType::SELECT,
                FieldValueType::TYPE_ARRAY,
            ],
            'Datetime' => [
                FieldType::DATETIME,
                FieldValueType::TYPE_TEXT,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getValueTypeReturnsExpectedFieldValuesDataProvider
     */
    public function getValueTypeReturnsExpectedFieldValues(string $fieldType, int $expected): void
    {
        $this->subject->setType($fieldType);
        self::assertEquals($expected, $this->subject->getValueType());
    }

    /**
     * @test
     */
    public function getPartialNameReturnsFieldTypeInUppercase(): void
    {
        $this->subject->setType('input');
        self::assertEquals('Input', $this->subject->getPartialName());
    }

    public static function getDatepickermodeTypeDataProvider(): array
    {
        return [
            'datetime-local' => [
                1,
                'datetime-local',
            ],
            'time' => [
                2,
                'time',
            ],
            'date' => [
                0,
                'date',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getDatepickermodeTypeDataProvider
     */
    public function getDatepickermodeTypeReturnsExpectedValue(int $datepickerMode, string $expected): void
    {
        $this->subject->setDatepickermode($datepickerMode);
        self::assertEquals($expected, $this->subject->getDatepickermodeType());
    }

    /**
     * @test
     */
    public function getTextReturnsInitialValue(): void
    {
        self::assertEmpty($this->subject->getText());
    }

    /**
     * @test
     */
    public function setTextSetsTextForString(): void
    {
        $this->subject->setText('TYPO3');
        self::assertEquals('TYPO3', $this->subject->getText());
    }

    /**
     * @test
     */
    public function getDatepickermodeReturnsInitialValue(): void
    {
        self::assertEquals(0, $this->subject->getDatepickermode());
    }

    /**
     * @test
     */
    public function setDatepickermodeSetsValueForInt(): void
    {
        $this->subject->setDatepickermode(2);
        self::assertEquals(2, $this->subject->getDatepickermode());
    }

    /**
     * @test
     */
    public function getFeuserValueReturnInitialValue(): void
    {
        self::assertEquals('', $this->subject->getFeuserValue());
    }

    /**
     * @test
     */
    public function setFeuserValueSetsValueForString(): void
    {
        $expected = 'John';
        $this->subject->setFeuserValue($expected);
        self::assertEquals($expected, $this->subject->getFeuserValue());
    }
}
