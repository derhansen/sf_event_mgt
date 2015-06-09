<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Event;

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
 * SimultaneousRegistrations ViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
