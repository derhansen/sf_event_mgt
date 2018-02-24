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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * PrefillField ViewHelper for registration fields
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillFieldViewHelper extends AbstractViewHelper
{
    /**
     * Returns a string to be used as prefill value for the given registration field (type=input). If the form
     * has already been submitted, the submitted value for the field is returned.
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration\Field $registrationField
     * @return string
     */
    public function render($registrationField)
    {
        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->controllerContext->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            return $this->getFieldValueFromArguments($originalRequest->getArguments(), $registrationField->getUid());
        } else {
            return $registrationField->getDefaultValue();
        }
    }

    /**
     * Returns the submitted value for the given field uid
     *
     * @param array $submittedValues
     * @param int $fieldUid
     * @return string
     */
    protected function getFieldValueFromArguments($submittedValues, $fieldUid)
    {
        $result = '';
        foreach ($submittedValues['registration']['fieldValues'] as $fieldValue) {
            if ((int)$fieldValue['field'] === $fieldUid) {
                $result = $fieldValue['value'];
            }
        }
        return $result;
    }
}
