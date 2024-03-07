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
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * PrefillField ViewHelper for registration fields
 */
class PrefillFieldViewHelper extends AbstractPrefillViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('registrationField', 'object', 'The registrationField object', true);
    }

    /**
     * Returns a string to be used as prefill value for the given registration field (type=input). If the form
     * has already been submitted, the submitted value for the field is returned.
     *
     * 1. Default value
     * 2. fe_user record value
     */
    public function render(): string
    {
        /** @var Field $registrationField */
        $registrationField = $this->arguments['registrationField'];

        // If mapping errors occurred for form, return value that has been submitted from POST data
        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $this->renderingContext->getRequest()->getAttribute('extbase');
        $originalRequest = $extbaseRequestParameters->getOriginalRequest();

        if ($originalRequest) {
            $registrationData = $originalRequest->getParsedBody()[$this->getPluginNamespace($originalRequest)] ?? [];
            return $this->getFieldValueFromSubmittedData($registrationData, $registrationField->getUid());
        }

        $value = $registrationField->getDefaultValue();
        return $this->prefillFromFeuserData($registrationField, $value);
    }

    /**
     * Returns the submitted value for the given field uid
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

    /**
     * Prefills $value with fe_users data if configured in registration field
     *
     * @param Field $field
     * @param string $value
     * @return string
     */
    protected function prefillFromFeuserData(Field $field, string $value): string
    {
        if (!$this->getTypoScriptFrontendController() ||
            !$this->getFrontendUser()->user ||
            $field->getFeuserValue() === '' ||
            !array_key_exists($field->getFeuserValue(), $this->getFrontendUser()->user)
        ) {
            return $value;
        }

        return (string)$this->getFrontendUser()->user[$field->getFeuserValue()];
    }

    protected function getFrontendUser(): FrontendUserAuthentication
    {
        return $this->getTypoScriptFrontendController()->fe_user;
    }

    protected function getTypoScriptFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'] ?? null;
    }
}
