<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Utility\FieldType;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Field extends AbstractEntity
{
    protected string $title = '';
    protected string $type = '';
    protected bool $required = false;
    protected string $placeholder = '';
    protected string $defaultValue = '';
    protected string $settings = '';
    protected ?Event $event = null;
    protected string $text = '';
    protected int $datepickermode = 0;
    protected string $feuserValue = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getDefaultValue(): string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(string $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function getSettings(): string
    {
        return $this->settings;
    }

    public function setSettings(string $settings): void
    {
        $this->settings = $settings;
    }

    /**
     * Explodes the given string and returns an array of options for check and radio fields
     *
     * @return array
     */
    public function getSettingsForOption(): array
    {
        $options = [];
        $string = str_replace('[\n]', PHP_EOL, $this->settings);
        $settingsField = GeneralUtility::trimExplode(PHP_EOL, $string, true);
        foreach ($settingsField as $line) {
            $settings = GeneralUtility::trimExplode('|', $line, false);
            $value = ($settings[1] ?? $settings[0]);
            $label = $settings[0];
            $options[] = [
                'label' => $label,
                'value' => $value,
                'selected' => $this->defaultValue === $value ? 1 : 0,
            ];
        }

        return $options;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }

    /**
     * Returns the field valueType
     *
     * @return int
     */
    public function getValueType(): int
    {
        $valueTypes = [
            FieldType::INPUT => FieldValueType::TYPE_TEXT,
            FieldType::CHECK => FieldValueType::TYPE_ARRAY,
            FieldType::RADIO => FieldValueType::TYPE_TEXT,
            FieldType::TEXTAREA => FieldValueType::TYPE_TEXT,
            FieldType::TEXT => FieldValueType::TYPE_TEXT,
            FieldType::DIVIDER => FieldValueType::TYPE_TEXT,
            FieldType::SELECT => FieldValueType::TYPE_ARRAY,
        ];
        if (isset($valueTypes[$this->type])) {
            return $valueTypes[$this->type];
        }

        return FieldValueType::TYPE_TEXT;
    }

    public function getPartialName(): string
    {
        return ucfirst($this->type);
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getDatepickermode(): int
    {
        return $this->datepickermode;
    }

    public function setDatepickermode(int $datepickermode): void
    {
        $this->datepickermode = $datepickermode;
    }

    public function getDatepickermodeType(): string
    {
        switch ($this->datepickermode) {
            case 1:
                return 'datetime-local';
            case 2:
                return 'time';
            default:
                return 'date';
        }
    }

    public function getFeuserValue(): string
    {
        return $this->feuserValue;
    }

    public function setFeuserValue(string $feuserValue): void
    {
        $this->feuserValue = $feuserValue;
    }
}
