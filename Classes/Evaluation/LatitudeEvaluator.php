<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Evaluation;

use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * LatitudeEvaluator
 */
class LatitudeEvaluator
{
    /**
     * Validates the given latitude value (between -90 and 90 degrees)
     * @see https://developers.google.com/maps/documentation/javascript/reference?hl=fr#LatLng
     */
    public function evaluateFieldValue(string $value, string $is_in, bool &$set)
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
