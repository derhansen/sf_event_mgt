<?php
namespace DERHANSEN\SfEventMgt\Evaluation;

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

use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * LatitudeEvaluator
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LatitudeEvaluator
{

    /**
     * Validates the given latitude value (between -90 and 90 degrees)
     * @see https://developers.google.com/maps/documentation/javascript/reference?hl=fr#LatLng
     *
     * @param mixed $value The value that has to be checked
     * @param string $is_in Is-In String
     * @param int $set Determines if the field can be set (value correct) or not
     *
     * @return string The new value of the field
     */
    public function evaluateFieldValue($value, $is_in, &$set)
    {
        $newValue = '0.000000';
        $set = true;
        if (MathUtility::canBeInterpretedAsFloat($value) &&
            ((float)$value >= -90 && (float)$value <= 90)
        ) {
            $newValue = number_format((float)$value, 6);
        }
        return $newValue;
    }
}
