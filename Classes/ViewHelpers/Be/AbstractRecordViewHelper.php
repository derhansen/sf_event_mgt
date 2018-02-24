<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Be;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Abstract Record viewhelper for backend links
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
abstract class AbstractRecordViewHelper extends AbstractViewHelper
{
    /**
     * Returns a moduleToken for the extension
     *
     * @return string
     */
    protected function getModuleToken()
    {
        return '&moduleToken=' . FormProtectionFactory::get()->generateToken(
            'moduleCall',
            'web_SfEventMgtTxSfeventmgtM1'
        );
    }
}
