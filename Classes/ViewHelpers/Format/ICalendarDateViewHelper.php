<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Format;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Torben Hansen <derhansen@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ICalendar Description viewhelper
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
