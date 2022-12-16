<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

class ArrayUtility
{
    /**
     * Check if the given value is JSON Array
     */
    public static function isJsonArray(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return is_array(json_decode($value, true));
    }
}
