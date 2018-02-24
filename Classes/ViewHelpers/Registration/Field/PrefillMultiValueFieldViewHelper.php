<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMapper;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * PrefillMultiValueField ViewHelper for registration fields
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillMultiValueFieldViewHelper extends AbstractViewHelper
{
    /**
     * @var \TYPO3\CMS\Extbase\Property\PropertyMapper
     */
    protected $propertyMapper = null;

    /**
     * @param PropertyMapper $propertyMapper
     */
    public function injectPropertyMapper(\TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper)
    {
        $this->propertyMapper = $propertyMapper;
    }

    /**
     * Returns, if the given $currentValue is selected/checked for the given registration field
     * If no originalRequest exist (form is not submitted), true is returned if the given $currentValue
     * matches the default value of the field
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field $registrationField
     * @param string $currentValue
     * @return bool
     */
    public function render($registrationField, $currentValue)
    {
        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->controllerContext->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            return $this->getFieldValueFromArguments(
                $originalRequest->getArguments(),
                $registrationField->getUid(),
                $currentValue
            );
        } else {
            return $this->getFieldValueFromDefaultProperty($registrationField, $currentValue);
        }
    }

    /**
     * Returns the submitted value for the given field uid
     *
     * @param array $submittedValues
     * @param int $fieldUid
     * @param string $currentValue
     * @return bool
     */
    protected function getFieldValueFromArguments($submittedValues, $fieldUid, $currentValue)
    {
        $result = false;
        foreach ($submittedValues['registration']['fieldValues'] as $fieldValueArray) {
            /** @var FieldValue $fieldValue */
            $fieldValue = $this->propertyMapper->convert($fieldValueArray, FieldValue::class);
            if ($fieldValue->getField()->getUid() === $fieldUid) {
                $result = $this->isGivenValueSelected($fieldValue, $currentValue);
            }
        }
        return $result;
    }

    /**
     * @param FieldValue $fieldValue
     * @param string $currentValue
     * @return bool
     */
    protected function isGivenValueSelected($fieldValue, $currentValue)
    {
        $fieldValue = $fieldValue->getValue();
        if (is_array($fieldValue)) {
            return in_array($currentValue, $fieldValue);
        } else {
            return $currentValue === $fieldValue;
        }
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field $registrationField
     * @param string $currentValue
     * @return bool
     */
    protected function getFieldValueFromDefaultProperty($registrationField, $currentValue)
    {
        $defaultValues = GeneralUtility::trimExplode(',', $registrationField->getDefaultValue());
        return in_array($currentValue, $defaultValues);
    }
}
