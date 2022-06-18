<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\ArrayUtility;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Answer
 */
class FieldValue extends AbstractEntity
{
    /**
     * Annotation is required, so propertyMapper will find a suiteable typeConverter
     *
     * @var string
     */
    protected string $value = '';

    protected int $valueType = FieldValueType::TYPE_TEXT;
    protected ?Field $field = null;
    protected ?Registration $registration = null;

    /**
     * Returns value depending on the valueType
     *
     * @return string|array
     */
    public function getValue()
    {
        $value = $this->value;
        if ($this->getField() && $this->getField()->getValueType() === FieldValueType::TYPE_ARRAY) {
            if (ArrayUtility::isJsonArray($value)) {
                $value = json_decode($value, true);
            } else {
                $value = [$this->value];
            }
        }

        return $value;
    }

    /**
     * Returns the field value for CSV export
     *
     * @return string
     */
    public function getValueForCsvExport(): string
    {
        $value = $this->value;
        if ($this->getField() && $this->getField()->getValueType() === FieldValueType::TYPE_ARRAY &&
            ArrayUtility::isJsonArray($value)
        ) {
            $value = implode(',', json_decode($value, true));
        }

        return $value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): void
    {
        $this->field = $field;
    }

    public function getRegistration(): ?Registration
    {
        return $this->registration;
    }

    public function setRegistration(?Registration $registration): void
    {
        $this->registration = $registration;
    }

    public function getValueType(): int
    {
        return $this->valueType;
    }

    public function setValueType(int $valueType): void
    {
        $this->valueType = $valueType;
    }
}
