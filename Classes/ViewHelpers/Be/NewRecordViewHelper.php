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
 * ViewHelper for a backend link that generates a new Event
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class NewRecordViewHelper extends AbstractRecordViewHelper
{
    /**
     * Renders a new record link
     *
     * @todo: Remove condition, when TYPO3 6.2 is deprecated
     *
     * @return string
     */
    public function render()
    {
        $pid = (int)GeneralUtility::_GET('id');

        if (GeneralUtility::compat_version('7.6')) {
            $parameters = [
                'edit[tx_sfeventmgt_domain_model_event][' . $pid . ']' => 'new',
            ];
            $parameters['returnUrl'] = 'index.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();
            $url = BackendUtility::getModuleUrl('record_edit', $parameters);
        } else {
            $returnUrl = 'mod.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();
            $url = 'alt_doc.php?edit[tx_sfeventmgt_domain_model_event][' . $pid .
                ']=new&returnUrl=' . urlencode($returnUrl);
        }
        return $url;
    }
}
