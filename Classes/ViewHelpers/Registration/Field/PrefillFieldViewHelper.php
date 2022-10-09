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

/**
 * PrefillField ViewHelper for registration fields
 */
class PrefillFieldViewHelper extends AbstractPrefillViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('registrationField', 'object', 'The registrationField object', true);
    }

    /**
     * Returns a string to be used as prefill value for the given registration field (type=input). If the form
     * has already been submitted, the submitted value for the field is returned.
     *
     * @return string
     */
    public function render(): string
    {
        /** @var Field $registrationField */
        $registrationField = $this->arguments['registrationField'];

        // If mapping errors occured for form, return value that has been submitted from POST data
        $originalRequest = $this->renderingContext->getRequest()->getOriginalRequest();

        if ($originalRequest) {
            $registrationData = $originalRequest->getParsedBody()[$this->getPluginNamespace($originalRequest)] ?? [];
            return $this->getFieldValueFromSubmittedData($registrationData, $registrationField->getUid());
        }

        return $registrationField->getDefaultValue();
    }

    /**
     * Returns the submitted value for the given field uid
     *
     * @param array $submittedData
     * @param int $fieldUid
     * @return string
     */
    protected function getFieldValueFromSubmittedData(array $submittedData, int $fieldUid): string
    {
        $result = '';
        foreach ($submittedData['registration']['fields'] ?? [] as $submittedFieldUid => $fieldValue) {
            if ((int)$submittedFieldUid === $fieldUid) {
                $result = $fieldValue;
            }
        }

        return $result;
    }
}
