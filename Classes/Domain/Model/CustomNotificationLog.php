<?php
namespace DERHANSEN\SfEventMgt\Domain\Model;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * CustomNotificationLog
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CustomNotificationLog extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Event
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    protected $event = null;

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
     * @param string $details Details
     *
     * @return void
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
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     *
     * @return void
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Returns the event
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets emailsSent
     *
     * @param int $emailsSent E-Mails sent
     *
     * @return void
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
     * @return \DateTime
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

    /**
     * Sets the timestamp
     *
     * @param \DateTime $tstamp Tstamp
     *
     * @return void
     */
    public function setTstamp(\DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * Returns the backend user
     *
     * @return \TYPO3\CMS\Beuser\Domain\Model\BackendUser
     */
    public function getCruserId()
    {
        return $this->cruserId;
    }

    /**
     * Sets the backend user
     *
     * @param \TYPO3\CMS\Beuser\Domain\Model\BackendUser $cruserId CruserId
     *
     * @return void
     */
    public function setCruserId($cruserId)
    {
        $this->cruserId = $cruserId;
    }
}
