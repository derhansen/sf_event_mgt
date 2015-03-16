<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Event;

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
 * SimultaneousRegistrations ViewHelper
 */
class SimultaneousRegistrationsViewHelper extends AbstractViewHelper {

	/**
	 * Returns an array with the amount of possible simultaneous registrations
	 * respecting the maxRegistrationsPerUser setting and also respects the amount
	 * of remaining free places.
	 *
	 * The returned array index starts at 1 if at least one registration is possible
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 *
	 * @return array
	 */
	public function render($event) {
		$minPossibleRegistrations = 1;
		$maxPossibleRegistrations = $event->getFreePlaces();
		$result = array($maxPossibleRegistrations);
		if ($event->getMaxRegistrationsPerUser() <= $maxPossibleRegistrations) {
			$maxPossibleRegistrations = $event->getMaxRegistrationsPerUser();
		}
		if ($maxPossibleRegistrations >= $minPossibleRegistrations) {
			$arrayWithZeroAsIndex = range($minPossibleRegistrations, $maxPossibleRegistrations);
			$result = array_combine(range(1, count($arrayWithZeroAsIndex)), $arrayWithZeroAsIndex);
		}
		return $result;
	}

}
