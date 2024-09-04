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
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;

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
        /** @var Request $request */
        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);
        /** @var ExtbaseRequestParameters $extbaseRequestParameters */
        $extbaseRequestParameters = $request->getAttribute('extbase');
        $originalRequest = $extbaseRequestParameters->getOriginalRequest();

        if ($originalRequest) {
            $registrationData = $originalRequest->getParsedBody()[$this->getPluginNamespace($originalRequest)] ?? [];
            return $this->getFieldValueFromSubmittedData($registrationData, $registrationField->getUid());
        }

        $frontendUser = $request->getAttribute('frontend.user');
        $value = $registrationField->getDefaultValue();
        return $this->prefillFromFeuserData($frontendUser, $registrationField, $value);
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
     */
    protected function prefillFromFeuserData(
        FrontendUserAuthentication $frontendUser,
        Field $field,
        string $value
    ): string {
        if (!$frontendUser->user ||
            $field->getFeuserValue() === '' ||
            !array_key_exists($field->getFeuserValue(), $frontendUser->user)
        ) {
            return $value;
        }

        return (string)$frontendUser->user[$field->getFeuserValue()];
    }
}
