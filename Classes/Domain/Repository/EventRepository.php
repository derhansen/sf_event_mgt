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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;

/**
 * The repository for Events
 */
class EventRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * Disable the use of storage records, because the StoragePage can be set
	 * in the plugin
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->defaultQuerySettings = $this->objectManager->get('Tx_Extbase_Persistence_Typo3QuerySettings');
		$this->defaultQuerySettings->setRespectStoragePage(FALSE);
	}

	/**
	 * Returns the objects of this repository matching the given demand
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findDemanded(EventDemand $eventDemand) {
		$constraints = array();
		$query = $this->createQuery();
		$this->setStoragePageConstraint($query, $eventDemand, $constraints);
		$this->setDisplayModeConstraint($query, $eventDemand, $constraints);
		$this->setCategoryConstraint($query, $eventDemand, $constraints);
		$this->setStartEndDateConstraint($query, $eventDemand, $constraints);
		$this->setTitleConstraint($query, $eventDemand, $constraints);
		$this->setTopEventConstraint($query, $eventDemand, $constraints);

		if (count($constraints) > 0) {
			$query->matching($query->logicalAnd($constraints));
		}
		return $query->execute();
	}

	/**
	 * Sets the storagePage constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setStoragePageConstraint($query, $eventDemand, &$constraints) {
		if ($eventDemand->getStoragePage() != '') {
			$pidList = GeneralUtility::intExplode(',', $eventDemand->getStoragePage(), TRUE);
			$constraints[] = $query->in('pid', $pidList);
		}
	}

	/**
	 * Sets the displayMode constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setDisplayModeConstraint($query, $eventDemand, &$constraints) {
		switch ($eventDemand->getDisplayMode()) {
			case 'future':
				$constraints[] = $query->greaterThan('startdate', $eventDemand->getCurrentDateTime());
				break;
			case 'past':
				$constraints[] = $query->lessThanOrEqual('enddate', $eventDemand->getCurrentDateTime());
				break;
			default:
		}
	}

	/**
	 * Sets the category constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setCategoryConstraint($query, $eventDemand, &$constraints) {
		if ($eventDemand->getCategory() != '') {
			$categoryConstraints = array();
			$categories = GeneralUtility::intExplode(',', $eventDemand->getCategory(), TRUE);
			foreach ($categories as $category) {
				$categoryConstraints[] = $query->contains('category', $category);
			}
			if (count($categoryConstraints) > 0) {
				$constraints[] = $query->logicalOr($categoryConstraints);
			}
		}
	}

	/**
	 * Sets the start- and enddate constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setStartEndDateConstraint($query, $eventDemand, &$constraints) {
		/* StartDate */
		if ($eventDemand->getStartDate() !== NULL) {
			$constraints[] = $query->greaterThanOrEqual('startdate', $eventDemand->getStartDate());
		}

		/* EndDate */
		if ($eventDemand->getEndDate() !== NULL) {
			$constraints[] = $query->lessThanOrEqual('enddate', $eventDemand->getEndDate());
		}
	}

	/**
	 * Sets the title constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setTitleConstraint($query, $eventDemand, &$constraints) {
		if ($eventDemand->getTitle() !== '') {
			$constraints[] = $query->like('title', '%' . $eventDemand->getTitle() . '%', FALSE);
		}
	}

	/**
	 * Sets the topEvent constraint to the given constraints array
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
	 * @param array $constraints
	 * @return void
	 */
	protected function setTopEventConstraint($query, $eventDemand, &$constraints) {
		if ($eventDemand->getTopEventRestriction() > 0) {
			$constraints[] = $query->equals('topEvent', (bool)($eventDemand->getTopEventRestriction() - 1));
		}
	}

}