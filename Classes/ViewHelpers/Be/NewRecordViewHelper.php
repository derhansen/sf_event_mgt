<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Be;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
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
     * @return string
     */
    public function render()
    {
        $pid = (int)GeneralUtility::_GET('id');

        $parameters = [
            'edit[tx_sfeventmgt_domain_model_event][' . $pid . ']' => 'new',
        ];
        $parameters['returnUrl'] = 'index.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();
        return BackendUtility::getModuleUrl('record_edit', $parameters);
    }
}
