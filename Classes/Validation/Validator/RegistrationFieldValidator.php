<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;

/**
 * RegistrationFieldValidator
 */
class RegistrationFieldValidator extends AbstractValidator
{
    /**
     * Validates the additional fields of the given registration.
     *
     * If $registration->getEvent() is null, the registration does not contain any registration fields
     *
     * @param Registration $value
     */
    protected function isValid(mixed $value): void
    {
        if ($value->getEvent() === null || ($value->getFieldValues()->count() === 0 &&
            $value->getEvent()->getRegistrationFields()->count() === 0)) {
            return;
        }

        /** @var Registration\Field $registrationField */
        foreach ($value->getEvent()->getRegistrationFields() as $registrationField) {
            $this->validateField($registrationField, $value->getFieldValues());
        }
    }

    /**
     * Validates the given registrationField
     */
    protected function validateField(Registration\Field $registrationField, ObjectStorage $fieldValues): void
    {
        if (!$registrationField->getRequired()) {
            return;
        }

        $validator = $this->getNotEmptyValidator();

        $fieldValue = $this->getFieldValue($registrationField, $fieldValues);
        $validationResult = $validator->validate($fieldValue);
        if ($validationResult->hasErrors()) {
            foreach ($validationResult->getErrors() as $error) {
                $this->result->forProperty('fields.' . $registrationField->getUid())->addError($error);
            }
        }
    }

    protected function getNotEmptyValidator(): NotEmptyValidator
    {
        $validator = new NotEmptyValidator();
        $validator->setOptions([
            'nullMessage' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:validation.required_field',
            'emptyMessage' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:validation.required_field',
        ]);

        return $validator;
    }

    /**
     * Returns the value for the given registrationField from the given fieldValues
     *
     * @return string|array
     */
    protected function getFieldValue(Registration\Field $registrationField, ObjectStorage $fieldValues)
    {
        $result = '';
        /** @var Registration\FieldValue $fieldValue */
        foreach ($fieldValues as $fieldValue) {
            if ($fieldValue->getField()->getUid() === $registrationField->getUid()) {
                $result = $fieldValue->getValue();
            }
        }

        // If field value is an array, then return empty string if it contains only empty values
        if (is_array($result) && $this->isArrayWithEmptyValues($result)) {
            $result = '';
        }

        return $result;
    }

    /**
     * Returns, if the given array contains only empty values
     */
    private function isArrayWithEmptyValues(array $array): bool
    {
        foreach ($array as $value) {
            if ($value !== '') {
                return false;
            }
        }

        return true;
    }
}
