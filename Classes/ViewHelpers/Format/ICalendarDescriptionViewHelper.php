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
