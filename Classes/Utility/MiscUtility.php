<?php
declare(strict_types=1);

namespace DERHANSEN\SfEventMgt\Utility;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

class MiscUtility
{
    /**
     * Returns, if the current TYPO3 version it 9.5 LTS
     *
     * @return bool
     */
    public static function isV9Lts(): bool
    {
        return version_compare(TYPO3_branch, '9.5', '=');
    }

    /**
     * Returns, if the current TYPO3 version it 8.7 LTS
     *
     * @return bool
     */
    public static function isV8Lts(): bool
    {
        return version_compare(TYPO3_branch, '8.7', '=');
    }
}
