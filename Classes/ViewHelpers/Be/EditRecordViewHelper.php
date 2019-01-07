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
 * ViewHelper for a backend link that should edit the given event UID
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EditRecordViewHelper extends AbstractRecordViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('uid', 'integer', 'Record UID', true);
    }

    /**
     * Renders a edit link for the given Event UID
     *
     * @return string
     */
    public function render()
    {
        $uid = $this->arguments['uid'];
        $pid = (int)GeneralUtility::_GET('id');
        $parameters = [
            'edit[tx_sfeventmgt_domain_model_event][' . (int)$uid . ']' => 'edit',
        ];
        $parameters['returnUrl'] = 'index.php?M=web_SfEventMgtTxSfeventmgtM1&id=' . $pid . $this->getModuleToken();

        return BackendUtility::getModuleUrl('record_edit', $parameters);
    }
}
