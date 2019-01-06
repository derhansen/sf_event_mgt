<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDateViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('date', 'datetime', 'The DateTime object', false);
    }

    /**
     * Formats the given date according to rfc5545
     *
     * @see http://tools.ietf.org/html/rfc5545#section-3.3.5
     * @return string
     */
    public function render()
    {
        $date = $this->arguments['date'];
        if ($date === null) {
            $date = $this->renderChildren();
        }
        if ($date instanceof \DateTime) {
            return gmdate('Ymd\THis\Z', $date->getTimestamp());
        }

        return '';
    }
}
