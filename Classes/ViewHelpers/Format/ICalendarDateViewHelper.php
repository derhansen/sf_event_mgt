<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ICalendarDateViewHelper extends AbstractViewHelper {

	/**
	 * Formats the given date according to rfc5545
	 *
	 * @param \DateTime $date The DateTime object
	 *
	 * @see http://tools.ietf.org/html/rfc5545#section-3.3.5
	 * @return string
	 */
	public function render($date = NULL) {
		if ($date === NULL) {
			$date = $this->renderChildren();
		}
		if ($date instanceof \DateTime) {
			return gmdate('Ymd\THis\Z', $date->getTimestamp());
		} else {
			return '';
		}
	}

}
