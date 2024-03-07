<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewHelper. Note, this ViewHelper does not escape output and should only be used
 * to process the iCal description field and any other foldable ICal text fields.
 */
class ICalendarDescriptionViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeOutput = false;

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('description', 'string', 'The description', false);
        $this->registerArgument('substractChars', 'integer', 'Amount of chars to substract from 75 in first line', false, 12);
    }

    /**
     * Formats the given description according to RFC 2445
     */
    public function render(): string
    {
        $description = $this->arguments['description'] ?? null;
        $substractChars = $this->arguments['substractChars'] ?? 0;
        $firstLineMaxChars = 75 - $substractChars;
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
        // Glue everything together, so every line is max 75 chars respecting max. length of first line
        if (mb_strlen($tmpDescription) > $firstLineMaxChars) {
            $newDescription = mb_substr($tmpDescription, 0, $firstLineMaxChars) . chr(10);
            $tmpDescription = mb_substr($tmpDescription, $firstLineMaxChars);
            $arrPieces = mb_str_split($tmpDescription, 75);
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
