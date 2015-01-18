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
 * CustomNotificationLog
 */
class CustomNotificationLog extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Event
	 *
	 * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
	 */
	protected $event = NULL;

	/**
	 * Details
	 *
	 * @var string
	 */
	protected $details;

	/**
	 * E-Mails sent
	 *
	 * @var int
	 */
	protected $emailsSent;

	/**
	 * Timestamp
	 *
	 * @var \DateTime
	 */
	protected $tstamp;

	/**
	 * Backend user
	 *
	 * @var \TYPO3\CMS\Beuser\Domain\Model\BackendUser
	 */
	protected $cruserId;

	/**
	 * Sets the details
	 *
	 * @param string $details
	 * @return void
	 */
	public function setDetails($details) {
		$this->details = $details;
	}

	/**
	 * Returns the details
	 *
	 * @return string
	 */
	public function getDetails() {
		return $this->details;
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
	 * Sets emailsSent
	 *
	 * @param int $emailsSent
	 * @return void
	 */
	public function setEmailsSent($emailsSent) {
		$this->emailsSent = $emailsSent;
	}

	/**
	 * Returns emailsSent
	 *
	 * @return int
	 */
	public function getEmailsSent() {
		return $this->emailsSent;
	}

	/**
	 * Returns tstamp
	 *
	 * @return \DateTime
	 */
	public function getTstamp() {
		return $this->tstamp;
	}

	/**
	 * Sets the timestamp
	 *
	 * @param \DateTime $tstamp
	 * @return void
	 */
	public function setTstamp($tstamp) {
		$this->tstamp = $tstamp;
	}

	/**
	 * Returns the backend user
	 *
	 * @return \TYPO3\CMS\Beuser\Domain\Model\BackendUser
	 */
	public function getCruserId() {
		return $this->cruserId;
	}

	/**
	 * Sets the backend user
	 *
	 * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $cruserId
	 * @return void
	 */
	public function setCruserId($cruserId) {
		$this->cruserId = $cruserId;
	}
}