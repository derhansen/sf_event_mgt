<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\ArrayUtility;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;

/**
 * Answer
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class FieldValue extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Value
     *
     * @var string
     */
    protected $value = '';

    /**
     * The type of the value
     *
     * @var int
     */
    protected $valueType = FieldValueType::TYPE_TEXT;

    /**
     * Field
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field
     */
    protected $field = null;

    /**
     * Registration
     *
     * @var Registration
     */
    protected $registration = null;

    /**
     * Returns value depending on the valueType
     *
     * @return string|array
     */
    public function getValue()
    {
        $value = $this->value;
        if ($this->getField()->getValueType() === FieldValueType::TYPE_ARRAY && ArrayUtility::isJsonArray($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }

    public function getValueForCsvExport()
    {
        $value = $this->value;
        if ($this->getField()->getValueType() === FieldValueType::TYPE_ARRAY && ArrayUtility::isJsonArray($value)) {
            $value = implode(',', json_decode($value, true));
        }

        return $value;
    }

    /**
     * Sets value
     *
     * @param string $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns field
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Sets field
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field $field
     * @return void
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Returns registration
     *
     * @return Registration
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * Sets registration
     *
     * @param Registration $registration
     * @return void
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * Sets value type
     *
     * @return int
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * Returns value type
     *
     * @param int $valueType
     */
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;
    }
}
