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
class ICalendarDateViewHelper extends AbstractViewHelper
{
    /**
     * Formats the given date according to rfc5545
     *
     * @param \DateTime $date The DateTime object
     *
     * @see http://tools.ietf.org/html/rfc5545#section-3.3.5
     * @return string
     */
    public function render($date = null)
    {
        if ($date === null) {
            $date = $this->renderChildren();
        }
        if ($date instanceof \DateTime) {
            return gmdate('Ymd\THis\Z', $date->getTimestamp());
        }

        return '';
    }
}
