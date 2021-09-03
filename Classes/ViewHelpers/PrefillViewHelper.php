<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

/**
 * Prefill ViewHelper
 */
class PrefillViewHelper extends AbstractPrefillViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('fieldname', 'string', 'FieldName', true);
        $this->registerArgument('prefillSettings', 'array', 'PrefillSettings', false, []);
    }

    /**
     * If the current field is available in POST data of the current request, return the value, otherwise
     * a property from fe_user (if logged in and if the given field is configured to be prefilled) is returned.
     *
     * @return string
     */
    public function render()
    {
        $fieldname = $this->arguments['fieldname'];
        $prefillSettings = $this->arguments['prefillSettings'];

        $request = $this->renderingContext->getRequest();
        $registrationData = $request->getParsedBody()[$this->getPluginNamespace($request)] ?? [];
        if (isset($registrationData['registration'][$fieldname])) {
            return $registrationData['registration'][$fieldname];
        }

        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->fe_user->user || empty($prefillSettings) ||
            !array_key_exists($fieldname, $prefillSettings)
        ) {
            return '';
        }

        return (string)($GLOBALS['TSFE']->fe_user->user[$prefillSettings[$fieldname]]);
    }
}
