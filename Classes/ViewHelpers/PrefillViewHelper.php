<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Prefill ViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillViewHelper extends AbstractViewHelper
{

    /**
     * Returns a property from fe_user (if logged in and if the given field is
     * configured to be prefilled)
     *
     * @param string $fieldname FieldName
     * @param array $prefillSettings PrefillSettings
     *
     * @return string
     */
    public function render($fieldname, $prefillSettings = [])
    {
        $piVars = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('tx_sfeventmgt_pievent');
        if (isset($piVars['registration'][$fieldname]) && $piVars['registration'][$fieldname] != '') {
            return $piVars['registration'][$fieldname];
        }
        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->loginUser || empty($prefillSettings) ||
            !array_key_exists($fieldname, $prefillSettings)
        ) {
            return '';
        }
        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->controllerContext->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            $submittedValues = $originalRequest->getArguments();
            return $submittedValues['registration'][$fieldname];
        }
        return strval($GLOBALS['TSFE']->fe_user->user[$prefillSettings[$fieldname]]);
    }

}
