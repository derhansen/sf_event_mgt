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
 * Registration
 */
class Registration extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Firstname
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $firstname = '';

	/**
	 * Lastname
	 *
	 * @var string
	 * @validate NotEmpty
	 */
	protected $lastname = '';

	/**
	 * Title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * company
	 *
	 * @var string
	 */
	protected $company = '';

	/**
	 * Address
	 *
	 * @var string
	 */
	protected $address = '';

	/**
	 * Zip
	 *
	 * @var integer
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
	 * Phone
	 *
	 * @var string
	 */
	protected $phone = '';

	/**
	 * E-Mail
	 *
	 * @var string
	 * @validate NotEmpty, EmailAddress
	 */
	protected $email = '';

	/**
	 * gender
	 *
	 * @var string
	 */
	protected $gender = '';

	/**
	 * Date of birth
	 *
	 * @var \DateTime
	 */
	protected $dateOfBirth = NULL;

	/**
	 * Confirmed
	 *
	 * @var boolean
	 */
	protected $confirmed = FALSE;

	/**
	 * Paid
	 *
	 * @var boolean
	 */
	protected $paid = FALSE;

	/**
	 * Notes
	 *
	 * @var string
	 */
	protected $notes = '';

	/**
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
	 */
	protected $event = NULL;

	/**
	 * DateTime until the registration must be confirmed
	 *
	 * @var \DateTime
	 */
	protected $confirmationUntil = NULL;

	/**
	 * Indicates if record is hidden
	 *
	 * @var bool
	 */
	protected $hidden = FALSE;

	/**
	 * Returns the firstname
	 *
	 * @return string $firstname
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * Sets the firstname
	 *
	 * @param string $firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * Returns the lastname
	 *
	 * @return string $lastname
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * Sets the lastname
	 *
	 * @param string $lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
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
	 * Returns the company
	 *
	 * @return string $company
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * Sets the company
	 *
	 * @param string $company
	 * @return void
	 */
	public function setCompany($company) {
		$this->company = $company;
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
	 * @return integer $zip
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * Sets the zip
	 *
	 * @param integer $zip
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
	 * Returns the phone
	 *
	 * @return string $phone
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * Sets the phone
	 *
	 * @param string $phone
	 * @return void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * Returns the email
	 *
	 * @return string $email
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Sets the email
	 *
	 * @param string $email
	 * @return void
	 */
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	 * Returns the gender
	 *
	 * @return string $gender
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * Sets the gender
	 *
	 * @param string $gender
	 * @return void
	 */
	public function setGender($gender) {
		$this->gender = $gender;
	}

	/**
	 * Sets the date of birth
	 *
	 * @param \DateTime $dateOfBirth
	 * @return void
	 */
	public function setDateOfBirth($dateOfBirth) {
		$this->dateOfBirth = $dateOfBirth;
	}

	/**
	 * Returns the date of birth
	 *
	 * @return \DateTime
	 */
	public function getDateOfBirth() {
		return $this->dateOfBirth;
	}

	/**
	 * Returns the confirmed
	 *
	 * @return boolean $confirmed
	 */
	public function getConfirmed() {
		return $this->confirmed;
	}

	/**
	 * Sets the confirmed
	 *
	 * @param boolean $confirmed
	 * @return void
	 */
	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;
	}

	/**
	 * Returns the boolean state of confirmed
	 *
	 * @return boolean
	 */
	public function isConfirmed() {
		return $this->confirmed;
	}

	/**
	 * Returns the paid
	 *
	 * @return boolean $paid
	 */
	public function getPaid() {
		return $this->paid;
	}

	/**
	 * Sets the paid
	 *
	 * @param boolean $paid
	 * @return void
	 */
	public function setPaid($paid) {
		$this->paid = $paid;
	}

	/**
	 * Returns the boolean state of paid
	 *
	 * @return boolean
	 */
	public function isPaid() {
		return $this->paid;
	}

	/**
	 * Sets the event
	 *
	 * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event
	 * @return void
	 */
	public function setEvent($event) {
		$this->event = $event;
	}

	/**
	 * Returns the event
	 *
	 * @return \DERHANSEN\SfEventMgt\Domain\Model\Event
	 */
	public function getEvent() {
		return $this->event;
	}

	/**
	 * Setter for notes
	 *
	 * @param string $notes
	 * @return void
	 */
	public function setNotes($notes) {
		$this->notes = $notes;
	}

	/**
	 * Getter for notes
	 *
	 * @return string
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * Sets confirmUntil
	 *
	 * @param \DateTime $confirmationUntil
	 * @return void
	 */
	public function setConfirmationUntil($confirmationUntil) {
		$this->confirmationUntil = $confirmationUntil;
	}

	/**
	 * Returns confirmationUntil
	 *
	 * @return \DateTime
	 */
	public function getConfirmationUntil() {
		return $this->confirmationUntil;
	}

	/**
	 * Sets hidden
	 *
	 * @param boolean $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Returns hidden
	 *
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

}