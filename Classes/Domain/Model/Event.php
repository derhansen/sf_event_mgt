<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

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
 * Event
 */
class Event extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Title
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $title = '';

	/**
	 * Teaser
	 *
	 * @var string
	 */
	protected $teaser = '';

	/**
	 * Description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Startdate and time
	 *
	 * @var \DateTime
	 */
	protected $startdate = NULL;

	/**
	 * Enddate and time
	 *
	 * @var \DateTime
	 */
	protected $enddate = NULL;

	/**
	 * Max participants
	 *
	 * @var integer
	 */
	protected $maxParticipants = 0;

	/**
	 * Max registrations per user
	 *
	 * @var integer
	 */
	protected $maxRegistrationsPerUser = 1;

	/**
	 * Price
	 *
	 * @var float
	 */
	protected $price = 0.0;

	/**
	 * Currency
	 *
	 * @var string
	 */
	protected $currency = '';

	/**
	 * Category
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Category>
	 */
	protected $category = NULL;

	/**
	 * Registration
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DERHANSEN\SfEventMgt\Domain\Model\Registration>
	 * @cascade remove
	 */
	protected $registration = NULL;

	/**
	 * Registration deadline date
	 *
	 * @var \DateTime
	 */
	protected $registrationDeadline = NULL;

	/**
	 * The image
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $image = NULL;

	/**
	 * Additional files
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
	 */
	protected $files = NULL;

	/**
	 * YouTube Embed code
	 *
	 * @var string
	 */
	protected $youtube = '';

	/**
	 * The Location
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
	 */
	protected $location = NULL;

	/**
	 * Enable registration
	 *
	 * @var bool
	 */
	protected $enableRegistration = FALSE;

	/**
	 * Link
	 *
	 * @var string
	 */
	protected $link;

	/**
	 * Top event
	 *
	 * @var bool
	 */
	protected $topEvent = FALSE;

	/**
	 * __construct
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties
	 * Do not modify this method!
	 * It will be rewritten on each save in the extension builder
	 * You may modify the constructor of this class instead
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->category = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->registration = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->image = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->files = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the title
	 *
	 * @return string $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the title
	 *
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the teaser
	 *
	 * @return string
	 */
	public function getTeaser() {
		return $this->teaser;
	}

	/**
	 * Sets the teaser
	 *
	 * @param string $teaser
	 * @return void
	 */
	public function setTeaser($teaser) {
		$this->teaser = $teaser;
	}

	/**
	 * Returns the description
	 *
	 * @return string $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the startdate
	 *
	 * @return \DateTime $startdate
	 */
	public function getStartdate() {
		return $this->startdate;
	}

	/**
	 * Sets the startdate
	 *
	 * @param \DateTime $startdate
	 * @return void
	 */
	public function setStartdate(\DateTime $startdate) {
		$this->startdate = $startdate;
	}

	/**
	 * Returns the enddate
	 *
	 * @return \DateTime $enddate
	 */
	public function getEnddate() {
		return $this->enddate;
	}

	/**
	 * Sets the enddate
	 *
	 * @param \DateTime $enddate
	 * @return void
	 */
	public function setEnddate(\DateTime $enddate) {
		$this->enddate = $enddate;
	}

	/**
	 * Returns the participants
	 *
	 * @return integer $participants
	 */
	public function getMaxParticipants() {
		return $this->maxParticipants;
	}

	/**
	 * Sets the participants
	 *
	 * @param integer $participants
	 * @return void
	 */
	public function setMaxParticipants($participants) {
		$this->maxParticipants = $participants;
	}

	/**
	 * Returns the price
	 *
	 * @return float $price
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * Sets the price
	 *
	 * @param float $price
	 * @return void
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * Returns the currency
	 *
	 * @return string $currency
	 */
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * Sets the currency
	 *
	 * @param string $currency
	 * @return void
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	/**
	 * Adds a Category
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Category $category
	 * @return void
	 */
	public function addCategory(\DERHANSEN\SfEventMgt\Domain\Model\Category $category) {
		$this->category->attach($category);
	}

	/**
	 * Removes a Category
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Category $categoryToRemove The Category to be removed
	 * @return void
	 */
	public function removeCategory(\DERHANSEN\SfEventMgt\Domain\Model\Category $categoryToRemove) {
		$this->category->detach($categoryToRemove);
	}

	/**
	 * Returns the category
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Sets the category
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $category
	 * @return void
	 */
	public function setCategory(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $category) {
		$this->category = $category;
	}

	/**
	 * Adds a Registration
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
	 * @return void
	 */
	public function addRegistration(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registration) {
		$this->registration->attach($registration);
	}

	/**
	 * Removes a Registration
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove
	 * @return void
	 */
	public function removeRegistration(\DERHANSEN\SfEventMgt\Domain\Model\Registration $registrationToRemove) {
		$this->registration->detach($registrationToRemove);
	}

	/**
	 * Returns the Registration
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration
	 */
	public function getRegistration() {
		return $this->registration;
	}

	/**
	 * Sets the Registration
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration
	 * @return void
	 */
	public function setRegistration(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $registration) {
		$this->registration = $registration;
	}

	/**
	 * Adds an image
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
	 * @return void
	 */
	public function addImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image) {
		$this->image->attach($image);
	}

	/**
	 * Removes an image
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove
	 * @return void
	 */
	public function removeImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $imageToRemove) {
		$this->image->detach($imageToRemove);
	}

	/**
	 * Returns the image
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Sets the image
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $image
	 * @return void
	 */
	public function setImage(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $image) {
		$this->image = $image;
	}

	/**
	 * Adds a file
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $file
	 * @return void
	 */
	public function addFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $file) {
		$this->files->attach($file);
	}

	/**
	 * Removes a file
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove
	 * @return void
	 */
	public function removeFiles(\TYPO3\CMS\Extbase\Domain\Model\FileReference $fileToRemove) {
		$this->files->detach($fileToRemove);
	}

	/**
	 * Returns the files
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
	 */
	public function getFiles() {
		return $this->files;
	}

	/**
	 * Sets the files
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $files
	 * @return void
	 */
	public function setFiles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $files) {
		$this->files = $files;
	}

	/**
	 * Returns YouTube embed code
	 *
	 * @return string
	 */
	public function getYoutube() {
		return $this->youtube;
	}

	/**
	 * Sets YouTube embed code
	 *
	 * @param string $youtube
	 * @return void
	 */
	public function setYoutube($youtube) {
		$this->youtube = $youtube;
	}

	/**
	 * Returns if the registration for this event is logically possible
	 *
	 * @return bool
	 */
	public function getRegistrationPossible() {
		$maxParticipantsNotReached = TRUE;
		if ($this->getMaxParticipants() > 0 && $this->getRegistration()->count() >= $this->maxParticipants) {
			$maxParticipantsNotReached = FALSE;
		}
		$deadlineNotReached = TRUE;
		if ($this->getRegistrationDeadline() != NULL && $this->getRegistrationDeadline() <= new \DateTime()) {
				$deadlineNotReached = FALSE;
		}
		return ($this->getStartdate() > new \DateTime()) && $maxParticipantsNotReached &&
			$this->getEnableRegistration() && $deadlineNotReached;
	}

	/**
	 * Returns the amount of free places
	 *
	 * @return int
	 */
	public function getFreePlaces() {
		return $this->maxParticipants - $this->getRegistration()->count();
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

	/**
	 * Returns the location
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * Sets enableRegistration
	 *
	 * @param boolean $enableRegistration
	 * @return void
	 */
	public function setEnableRegistration($enableRegistration) {
		$this->enableRegistration = $enableRegistration;
	}

	/**
	 * Returns if registration is enabled
	 *
	 * @return boolean
	 */
	public function getEnableRegistration() {
		return $this->enableRegistration;
	}

	/**
	 * Sets the registration deadline
	 *
	 * @param \DateTime $registrationDeadline
	 * @return void
	 */
	public function setRegistrationDeadline($registrationDeadline) {
		$this->registrationDeadline = $registrationDeadline;
	}

	/**
	 * Returns the registration deadline
	 *
	 * @return \DateTime
	 */
	public function getRegistrationDeadline() {
		return $this->registrationDeadline;
	}

	/**
	 * Sets the link
	 *
	 * @param string $link
	 * @return void
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Returns the link
	 *
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * Returns the uri of the link
	 *
	 * @return string
	 */
	public function getLinkUrl() {
		return $this->getLinkPart(0);
	}

	/**
	 * Returns the target of the link
	 *
	 * @return string
	 */
	public function getLinkTarget() {
		return $this->getLinkPart(1);
	}

	/**
	 * Returns the title of the link
	 *
	 * @return string
	 */
	public function getLinkTitle() {
		return $this->getLinkPart(3);
	}

	/**
	 * Splits link to an array respection that a title with more than one word is
	 * surrounded by quotation marks. Returns part of the link for usage in fluid
	 * viewhelpers.
	 *
	 * @param int $part The part
	 * @return string
	 */
	public function getLinkPart($part) {
		$linkArray = str_getcsv($this->link, ' ', '"');
		$ret = '';
		if (count($linkArray) >= $part) {
			$ret = $linkArray[$part];
		}
		if ($ret === '-') {
			$ret = '';
		}
		return $ret;
	}

	/**
	 * Sets topEvent
	 *
	 * @param boolean $topEvent
	 * @return void
	 */
	public function setTopEvent($topEvent) {
		$this->topEvent = $topEvent;
	}

	/**
	 * Returns if topEvent is checked
	 *
	 * @return boolean
	 */
	public function getTopEvent() {
		return $this->topEvent;
	}

	/**
	 * Returns max regisrations per user
	 *
	 * @return int
	 */
	public function getMaxRegistrationsPerUser() {
		return $this->maxRegistrationsPerUser;
	}

	/**
	 * Sets max registrations per user
	 *
	 * @param int $maxRegistrationsPerUser
	 * @return void
	 */
	public function setMaxRegistrationsPerUser($maxRegistrationsPerUser) {
		$this->maxRegistrationsPerUser = $maxRegistrationsPerUser;
	}

}