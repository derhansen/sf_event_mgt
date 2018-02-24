<?php
namespace DERHANSEN\SfEventMgt\Evaluation;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * LongitudeEvaluator
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LongitudeEvaluator
{

    /**
     * Validates the given longitude value (between -180 and 180 degrees)
     * @see https://developers.google.com/maps/documentation/javascript/reference?hl=fr#LatLng
     *
     * @param mixed $value The value that has to be checked.
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
            ((float)$value >= -180 && (float)$value <= 180)
        ) {
            $newValue = number_format((float)$value, 6);
        }
        return $newValue;
    }
}
