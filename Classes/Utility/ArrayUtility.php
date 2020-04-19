<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

/**
 * ArrayUtility
 */
class ArrayUtility
{
    /**
     * Check if String is JSON Array
     *
     * @param string $string
     * @return bool
     */
    public static function isJsonArray($string)
    {
        if (!is_string($string)) {
            return false;
        }

        return is_array(json_decode($string, true));
    }
}
