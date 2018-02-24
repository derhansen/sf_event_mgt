<?php
namespace DERHANSEN\SfEventMgt\Utility;

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
