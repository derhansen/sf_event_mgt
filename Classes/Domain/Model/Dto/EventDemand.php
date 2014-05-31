<?php
namespace SKYFILLERS\SfEventMgt\Domain\Model\Dto;

	/***************************************************************
	 *
	 *  Copyright notice
	 *
	 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
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
 * Event demand
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
	 * Sets the displayMode
	 *
	 * @param string $displayMode
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
		} else {
			return new \DateTime;
		}
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
	 * @return void
	 */
	public function getCategory() {
		return $this->category;
	}

}