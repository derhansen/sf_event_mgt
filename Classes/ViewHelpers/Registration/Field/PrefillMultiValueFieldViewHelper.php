<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\AbstractPrefillViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * PrefillMultiValueField ViewHelper for registration fields
 */
class PrefillMultiValueFieldViewHelper extends AbstractPrefillViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('registrationField', 'object', 'RegistrationField', true);
        $this->registerArgument('currentValue', 'strong', 'Current value', true);
    }

    /**
     * Returns, if the given $currentValue is selected/checked for the given registration field is selected
     * If no originalRequest exist (form is not submitted), true is returned if the given $currentValue
     * matches the default value of the field
     */
    public function render(): bool
    {
        /** @var Field $registrationField */
        $registrationField = $this->arguments['registrationField'];
        $currentValue = $this->arguments['currentValue'];

        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->renderingContext->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            $registrationData = $originalRequest->getParsedBody()[$this->getPluginNamespace($originalRequest)] ?? [];

            return $this->getFieldValueFromSubmittedData(
                $registrationData,
                $registrationField->getUid(),
                $currentValue
            );
        }

        return $this->getFieldValueFromDefaultProperty($registrationField, $currentValue);
    }

    /**
     * Returns if the submitted field value is selected
     */
    protected function getFieldValueFromSubmittedData(array $submittedData, int $fieldUid, string $currentValue): bool
    {
        $result = false;

        foreach ($submittedData['registration']['fields'] ?? [] as $submittedFieldUid => $fieldValue) {
            if ((int)$submittedFieldUid === $fieldUid) {
                $result = $this->isGivenValueSelected($fieldValue, $currentValue);
            }
        }

        return $result;
    }

    /**
     * @param mixed $fieldValue
     * @param string $currentValue
     * @return bool
     */
    protected function isGivenValueSelected($fieldValue, string $currentValue): bool
    {
        if (is_array($fieldValue)) {
            return in_array($currentValue, $fieldValue);
        }

        return $currentValue === $fieldValue;
    }

    protected function getFieldValueFromDefaultProperty(Field $registrationField, string $currentValue): bool
    {
        $defaultValues = GeneralUtility::trimExplode(',', $registrationField->getDefaultValue());

        return in_array($currentValue, $defaultValues);
    }
}
