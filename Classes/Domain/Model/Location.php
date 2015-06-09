<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

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
 * Location
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Address
	 *
	 * @var string
	 */
	protected $address = '';

	/**
	 * Zip
	 *
	 * @var int
	 * @validate Integer
	 */
	protected $zip = 0;

	/**
	 * City
	 *
	 * @var string
	 */
	protected $city = '';

	/**
	 * Country
	 *
	 * @var string
	 */
	protected $country = '';

	/**
	 * Description
	 *
	 * @var string
	 */
	protected $description = '';

	/**
	 * Longitude
	 *
	 * @var float
	 */
	protected $longitude = 0.0;

	/**
	 * Latitude
	 *
	 * @var float
	 */
	protected $latitide = 0.0;

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
	 * @param string $title The title
	 *
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Returns the address
	 *
	 * @return string $address
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Sets the address
	 *
	 * @param string $address
	 * @return void
	 */
	public function setAddress($address) {
		$this->address = $address;
	}

	/**
	 * Returns the zip
	 *
	 * @return int $zip
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * Sets the zip
	 *
	 * @param int $zip
	 * @return void
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	/**
	 * Returns the city
	 *
	 * @return string $city
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Sets the city
	 *
	 * @param string $city
	 * @return void
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * Returns the country
	 *
	 * @return string $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * Sets the country
	 *
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country) {
		$this->country = $country;
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
	 * @param string $description The description
	 *
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the longitude
	 *
	 * @return float
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Sets the the longitude
	 *
	 * @param float $longitude The longitude
	 *
	 * @return void
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

	/**
	 * Returns the latitude
	 *
	 * @return float
	 */
	public function getLatitide() {
		return $this->latitide;
	}

	/**
	 * Sets the latitude
	 *
	 * @param float $latitide The latitude
	 *
	 * @return void
	 */
	public function setLatitide($latitide) {
		$this->latitide = $latitide;
	}
}