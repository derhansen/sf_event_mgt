<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\Domain\Model\BackendUser;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * CustomNotificationLog
 */
class CustomNotificationLog extends AbstractEntity
{
    /**
     * Event
     *
     * @var Event
     */
    protected $event;

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
     * @var DateTime
     */
    protected $tstamp;

    /**
     * Backend user
     *
     * @var BackendUser
     */
    protected $cruserId;

    /**
     * Sets the details
     *
     * @param string $details Details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * Returns the details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Sets the event
     *
     * @param Event $event Event
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Returns the event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets emailsSent
     *
     * @param int $emailsSent E-Mails sent
     */
    public function setEmailsSent($emailsSent)
    {
        $this->emailsSent = $emailsSent;
    }

    /**
     * Returns emailsSent
     *
     * @return int
     */
    public function getEmailsSent()
    {
        return $this->emailsSent;
    }

    /**
     * Returns tstamp
     *
     * @return DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the timestamp
     *
     * @param DateTime $tstamp Tstamp
     */
    public function setTstamp(DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Returns the backend user
     *
     * @return BackendUser
     */
    public function getCruserId()
    {
        return $this->cruserId;
    }

    /**
     * Sets the backend user
     *
     * @param BackendUser $cruserId CruserId
     */
    public function setCruserId($cruserId)
    {
        $this->cruserId = $cruserId;
    }
}
