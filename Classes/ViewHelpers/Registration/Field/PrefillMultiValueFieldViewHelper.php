<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\FieldValue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Property\PropertyMapper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * PrefillMultiValueField ViewHelper for registration fields
 */
class PrefillMultiValueFieldViewHelper extends AbstractViewHelper
{
    /**
     * @var PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @param PropertyMapper $propertyMapper
     */
    public function injectPropertyMapper(PropertyMapper $propertyMapper)
    {
        $this->propertyMapper = $propertyMapper;
    }

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('registrationField', 'object', 'RegistrationField', true);
        $this->registerArgument('currentValue', 'strong', 'Current value', true);
    }

    /**
     * Returns, if the given $currentValue is selected/checked for the given registration field
     * If no originalRequest exist (form is not submitted), true is returned if the given $currentValue
     * matches the default value of the field
     *
     * @return bool
     */
    public function render()
    {
        /** @var Field $registrationField */
        $registrationField = $this->arguments['registrationField'];
        $currentValue = $this->arguments['currentValue'];

        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            return $this->getFieldValueFromArguments(
                $originalRequest->getArguments(),
                $registrationField->getUid(),
                $currentValue
            );
        }

        return $this->getFieldValueFromDefaultProperty($registrationField, $currentValue);
    }

    /**
     * Returns if the submitted field value is selected
     *
     * @param array $submittedValues
     * @param int $fieldUid
     * @param string $currentValue
     * @return bool
     */
    protected function getFieldValueFromArguments($submittedValues, $fieldUid, $currentValue)
    {
        $result = false;

        if (!isset($submittedValues['registration']['fieldValues'])) {
            return $result;
        }

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
        }

        return $currentValue === $fieldValue;
    }

    /**
     * @param Field $registrationField
     * @param string $currentValue
     * @return bool
     */
    protected function getFieldValueFromDefaultProperty($registrationField, $currentValue)
    {
        $defaultValues = GeneralUtility::trimExplode(',', $registrationField->getDefaultValue());

        return in_array($currentValue, $defaultValues);
    }

    /**
     * Shortcut for retrieving the request from the controller context
     *
     * @return Request
     */
    protected function getRequest()
    {
        return $this->renderingContext->getControllerContext()->getRequest();
    }
}
