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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Typo3VersionClass ViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Typo3VersionClassViewHelper extends AbstractRecordViewHelper
{
    /**
     * Uses GeneralUtility::compat_version to return a classname which can be used in backend views
     *
     * @todo: Remove condition, when TYPO3 6.2 is deprecated
     *
     * @return string
     */
    public function render()
    {
        if (GeneralUtility::compat_version('7.6')) {
            return 'typo3-76';
        } elseif (GeneralUtility::compat_version('6.2')) {
            return 'typo3-62';
        } else {
            return '';
        }
    }
}
