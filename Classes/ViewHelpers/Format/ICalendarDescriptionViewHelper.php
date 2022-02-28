<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewhelper. Note, this ViewHelper does not escape output and should only be used
 * to process the iCal description field.
 */
class ICalendarDescriptionViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('description', 'string', 'The description', false);
    }

    /**
     * Formats the given description according to RFC 2445
     *
     * @return string
     */
    public function render()
    {
        $description = $this->arguments['description'];
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

        /*
         * Glue everything together, so every line is max 75 octets/bytes (not chars) long
         * @see: https://www.rfc-editor.org/rfc/rfc5545#section-3.1
         *
         * Assumption for description text:
         * In worst case there are a max of 2 bytes per UTF8-character - averaged per line.
         */
        // Important: `strlen` checks for length in bytes instead of chars.
        if (strlen($tmpDescription) > 63) {
            // First line has max 63 bytes of content and the 12 byte long string "DESCRIPTION:" is added.
            // Split to max 31 chars: roughly equals max 63 bytes.
            $newDescription = mb_substr($tmpDescription, 0, 31);
            $tmpDescription = mb_substr($tmpDescription, 31);
            // Split to max 37 chars: roughly equals max 74 bytes (plus 1 byte for white-space).
            $arrPieces = mb_str_split($tmpDescription, 37);
            $newDescription .= chr(10) . ' ' . implode(chr(10) . ' ', $arrPieces);
        } else {
            $newDescription = $tmpDescription;
        }

        return $newDescription;
    }
}
