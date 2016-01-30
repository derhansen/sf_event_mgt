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
