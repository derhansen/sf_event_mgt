<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Prefill ViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillViewHelper extends AbstractViewHelper
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
     * Returns a property from fe_user (if logged in and if the given field is
     * configured to be prefilled)
     *
     * @return string
     */
    public function render()
    {
        $fieldname = $this->arguments['fieldname'];
        $prefillSettings = $this->arguments['prefillSettings'];
        $piVars = GeneralUtility::_GP('tx_sfeventmgt_pievent');
        if (isset($piVars['registration'][$fieldname]) && $piVars['registration'][$fieldname] !== '') {
            return $piVars['registration'][$fieldname];
        }
        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->fe_user->user || empty($prefillSettings) ||
            !array_key_exists($fieldname, $prefillSettings)
        ) {
            return '';
        }
        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            $submittedValues = $originalRequest->getArguments();

            return $submittedValues['registration'][$fieldname];
        }

        return (string)($GLOBALS['TSFE']->fe_user->user[$prefillSettings[$fieldname]]);
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
