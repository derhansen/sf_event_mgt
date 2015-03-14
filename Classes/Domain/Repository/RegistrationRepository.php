<?php
namespace DERHANSEN\SfEventMgt\Domain\Repository;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
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

/**
 * The repository for registrations
 */
class RegistrationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * Disable the use of storage records, because the StoragePage can be set
	 * in the plugin
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$this->defaultQuerySettings->setRespectStoragePage(FALSE);
	}

	/**
	 * Returns all registrations, where the confirmation date is less than the
	 * given date
	 *
	 * @param \datetime $dateNow
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
	 */
	public function findExpiredRegistrations($dateNow) {
		$constraints = array();
		$query = $this->createQuery();
		$constraints[] = $query->lessThanOrEqual('confirmationUntil', $dateNow);
		$constraints[] = $query->equals('confirmed', FALSE);
		return $query->matching($query->logicalAnd($constraints))->execute();
	}

	/**
	 * Returns all registrations for the given event with the given constraints
	 * Constraints are combined with a logical AND
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @param $findConstraints
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findNotificationRegistrations($event, $findConstraints) {
		$constraints = array();
		$query = $this->createQuery();
		$constraints[] = $query->equals('event', $event);
		$constraints[] = $query->equals('ignoreNotifications', FALSE);

		if (!is_array($findConstraints) || count($findConstraints) == 0) {
			return $query->matching($query->logicalAnd($constraints))->execute();
		}

		foreach ($findConstraints as $findConstraint => $value) {
			$condition = key($value);
			switch ($condition) {
				case 'equals':
					$constraints[] = $query->equals($findConstraint, $value[$condition]);
					break;
				case 'lessThan':
					$constraints[] = $query->lessThan($findConstraint, $value[$condition]);
					break;
				case 'lessThanOrEqual':
					$constraints[] = $query->lessThanOrEqual($findConstraint, $value[$condition]);
					break;
				case 'greaterThan':
					$constraints[] = $query->greaterThan($findConstraint, $value[$condition]);
					break;
				case 'greaterThanOrEqual':
					$constraints[] = $query->greaterThanOrEqual($findConstraint, $value[$condition]);
					break;
				default:
					throw new \InvalidArgumentException('An error occured - Unknown condition: ' . $condition);
			}
		}

		return $query->matching($query->logicalAnd($constraints))->execute();
	}

}