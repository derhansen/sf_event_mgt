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
class ICalendarDescriptionViewHelper extends AbstractViewHelper {

	/**
	 * Formats the given description according to RFC 2445
	 *
	 * @param string $description The description
	 *
	 * @return string
	 */
	public function render($description = NULL) {
		if ($description === NULL) {
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
