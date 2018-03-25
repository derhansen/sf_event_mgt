<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Utility\FieldType;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Field
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Field extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * The title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Type - possible values
     *
     *  "input", "radio", "check", "textarea"
     *
     * @var string
     */
    protected $type = '';

    /**
     * Field is required
     *
     * @var bool
     */
    protected $required = false;

    /**
     * Placeholder
     *
     * @var string
     */
    protected $placeholder = '';

    /**
     * Default value
     *
     * @var string
     */
    protected $defaultValue = '';

    /**
     * Settings
     *
     * @var string
     */
    protected $settings = '';

    /**
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    protected $event = null;

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the required flag
     *
     * @return bool
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Sets the required flag
     *
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Returns placeholder
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Sets placeholder
     *
     * @param string $placeholder
     * @return void
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * Returns default value
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets default value
     *
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * Returns settings
     *
     * @return string
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Sets settings
     *
     * @param string $settings
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Explodes the given string and returns an array of options for check and radio fields
     *
     * @return array
     */
    public function getSettingsForOption()
    {
        $options = [];
        $string = str_replace('[\n]', PHP_EOL, $this->settings);
        $settingsField = GeneralUtility::trimExplode(PHP_EOL, $string, true);
        foreach ($settingsField as $line) {
            $settings = GeneralUtility::trimExplode('|', $line, false);
            $value = (isset($settings[1]) ? $settings[1] : $settings[0]);
            $label = $settings[0];
            $options[] = [
                'label' => $label,
                'value' => $value,
                'selected' => $this->defaultValue === $value ? 1 : 0
            ];
        }

        return $options;
    }

    /**
     * Returns the event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the event
     *
     * @param Event $event
     * @return void
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * Returns the field valueType
     *
     * @return int
     */
    public function getValueType()
    {
        $valueTypes = [
            FieldType::INPUT => FieldValueType::TYPE_TEXT,
            FieldType::CHECK => FieldValueType::TYPE_ARRAY,
            FieldType::RADIO => FieldValueType::TYPE_TEXT,
            FieldType::TEXTAREA => FieldValueType::TYPE_TEXT
        ];
        if (isset($valueTypes[$this->type])) {
            return $valueTypes[$this->type];
        }

        return FieldValueType::TYPE_TEXT;
    }
}
