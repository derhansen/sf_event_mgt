<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

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

/**
 * Event demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Display mode
	 *
	 * @var string
	 */
	protected $displayMode = 'all';

	/**
	 * Storage page
	 *
	 * @var string
	 */
	protected $storagePage;

	/**
	 * Current DateTime
	 *
	 * @var \DateTime
	 */
	protected $currentDateTime = NULL;

	/**
	 * Category
	 *
	 * @var string
	 */
	protected $category;

	/**
	 * StartDate
	 *
	 * @var \DateTime
	 */
	protected $startDate = NULL;

	/**
	 * EndDate
	 *
	 * @var \DateTime
	 */
	protected $endDate = NULL;

	/**
	 * Top event
	 *
	 * @var int
	 */
	protected $topEventRestriction = 0;

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Order field
	 *
	 * @var string
	 */
	protected $orderField = '';

	/**
	 * Order direction
	 *
	 * @var string
	 */
	protected $orderDirection = '';

	/**
	 * Query limit
	 *
	 * @var int
	 */
	protected $queryLimit = NULL;

	/**
	 * Location
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
	 */
	protected $location = NULL;

	/**
	 * Sets the displayMode
	 *
	 * @param string $displayMode
	 * @return void
	 */
	public function setDisplayMode($displayMode) {
		$this->displayMode = $displayMode;
	}

	/**
	 * Returns the displayMode
	 *
	 * @return string
	 */
	public function getDisplayMode() {
		return $this->displayMode;
	}

	/**
	 * Sets the storage page
	 *
	 * @param string $storagePage
	 * @return void
	 */
	public function setStoragePage($storagePage) {
		$this->storagePage = $storagePage;
	}

	/**
	 * Returns the storage page
	 *
	 * @return string
	 */
	public function getStoragePage() {
		return $this->storagePage;
	}

	/**
	 * Sets the current DateTime
	 *
	 * @param \DateTime $currentDateTime
	 * @return void
	 */
	public function setCurrentDateTime($currentDateTime) {
		$this->currentDateTime = $currentDateTime;
	}

	/**
	 * Returns the current datetime
	 *
	 * @return \DateTime
	 */
	public function getCurrentDateTime() {
		if ($this->currentDateTime != NULL) {
			return $this->currentDateTime;
		}
		return new \DateTime;
	}

	/**
	 * Sets the category (seperated by comma)
	 *
	 * @param string $category
	 * @return void
	 */
	public function setCategory($category) {
		$this->category = $category;
	}

	/**
	 * Returns the category (seperated by comma)
	 *
	 * @return string
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Set the start date
	 *
	 * @param \DateTime $startDate
	 * @return void
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * Returns the start date
	 *
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * Set the end date
	 *
	 * @param \DateTime $endDate
	 * @return void
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * Get the end date
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * Set the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Get the title
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Returns topEventRestriction
	 *
	 * @return int
	 */
	public function getTopEventRestriction() {
		return $this->topEventRestriction;
	}

	/**
	 * Sets topEventRestriction
	 *
	 * @param int $topEventRestriction
	 * @return void
	 */
	public function setTopEventRestriction($topEventRestriction) {
		$this->topEventRestriction = $topEventRestriction;
	}

	/**
	 * Returns the order direction
	 *
	 * @return string
	 */
	public function getOrderDirection() {
		return $this->orderDirection;
	}

	/**
	 * Sets the order direction
	 *
	 * @param string $orderDirection
	 * @return void
	 */
	public function setOrderDirection($orderDirection) {
		$this->orderDirection = $orderDirection;
	}

	/**
	 * Returns the order field
	 *
	 * @return string
	 */
	public function getOrderField() {
		return $this->orderField;
	}

	/**
	 * Sets the order field
	 *
	 * @param string $orderField
	 * @return void
	 */
	public function setOrderField($orderField) {
		$this->orderField = $orderField;
	}

	/**
	 * Returns the query limit
	 *
	 * @return int
	 */
	public function getQueryLimit() {
		return $this->queryLimit;
	}

	/**
	 * Sets the query limit
	 *
	 * @param int $queryLimit
	 * @return void
	 */
	public function setQueryLimit($queryLimit) {
		$this->queryLimit = $queryLimit;
	}

	/**
	 * Returns the location
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Sets the location
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Location $location
	 * @return void
	 */
	public function setLocation($location) {
		$this->location = $location;
	}

}