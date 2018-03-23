<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDescriptionViewHelper extends AbstractViewHelper
{
    /**
     * Formats the given description according to RFC 2445
     *
     * @param string $description The description
     *
     * @return string
     */
    public function render($description = null)
    {
        if ($description === null) {
            $description = $this->renderChildren();
        }
        $tmpDescription = strip_tags($description);
        $tmpDescription = str_replace('&nbsp;', ' ', $tmpDescription);
        $tmpDescription = html_entity_decode($tmpDescription);
        // Replace carriage return
        $tmpDescription = str_replace(chr(13), '\n\n', $tmpDescription);
        // Strip new lines
        $tmpDescription = str_replace(chr(10), '', $tmpDescription);
        // Glue everything together, so every line is max 75 chars
        if (strlen($tmpDescription) > 75) {
            $newDescription = substr($tmpDescription, 0, 63) . chr(10);
            $tmpDescription = substr($tmpDescription, 63);
            $arrPieces = str_split($tmpDescription, 74);
            foreach ($arrPieces as &$value) {
                $value = ' ' . $value;
            }
            $newDescription .= implode(chr(10), $arrPieces);
        } else {
            $newDescription = $tmpDescription;
        }

        return $newDescription;
    }
}
