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
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
     * @param Registration $registration
     * @return bool
     */
    protected function isValid($registration)
    {
        $result = true;
        if ($registration->getEvent() === null || ($registration->getFieldValues()?->count() === 0 &&
            $registration->getEvent()->getRegistrationFields()->count() === 0)) {
            return $result;
        }

        /** @var Registration\Field $registrationField */
        foreach ($registration->getEvent()->getRegistrationFields() as $registrationField) {
            $validationResult = $this->validateField($registrationField, $registration->getFieldValues());
            if ($validationResult === false && $result === true) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Validates the given registrationField
     */
    protected function validateField(Registration\Field $registrationField, ObjectStorage $fieldValues): bool
    {
        $result = true;
        if (!$registrationField->getRequired()) {
            return $result;
        }

        $validator = $this->getNotEmptyValidator();

        $fieldValue = $this->getFieldValue($registrationField, $fieldValues);
        $validationResult = $validator->validate($fieldValue);
        if ($validationResult->hasErrors()) {
            $result = false;
            foreach ($validationResult->getErrors() as $error) {
                $this->result->forProperty('fields.' . $registrationField->getUid())->addError($error);
            }
        }

        return $result;
    }

    /**
     * Returns a notEmptyValidator
     */
    protected function getNotEmptyValidator(): NotEmptyValidator
    {
        return GeneralUtility::makeInstance(NotEmptyValidator::class);
    }

    /**
     * Returns the value for the given registrationField from the given fieldValues
     *
     * @param Registration\Field $registrationField
     * @param ObjectStorage $fieldValues
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

        // If field value is an array, then treat one single element with an empty string as an empty value
        if (is_array($result) && $result === [0 => '']) {
            $result = '';
        }

        return $result;
    }
}
