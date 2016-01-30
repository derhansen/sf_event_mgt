<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Be;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper for a backend link that should edit the given event UID
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EditRecordViewHelper extends AbstractRecordViewHelper
{
    /**
     * Renders a edit link for the given Event UID
     *
     * @todo: Remove condition, when TYPO3 6.2 is deprecated
     *
     * @param int $uid
     * @return string
     */
    public function render($uid)
    {
        $pid = (int)GeneralUtility::_GET('id');

        if (GeneralUtility::compat_version('7.6')) {
            $parameters = [
                'edit[tx_sfeventmgt_domain_model_event][' . (int)$uid . ']' => 'edit',
            ];
            $parameters['returnUrl'] = 'index.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();
            $url = BackendUtility::getModuleUrl('record_edit', $parameters);
        } else {
            $returnUrl = 'mod.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();
            $url = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][' . (int)$uid .
                ']=edit&returnUrl=' . urlencode($returnUrl);
        }
        return $url;
    }
}
