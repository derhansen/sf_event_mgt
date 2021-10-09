<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * CustomNotificationLog
 */
class CustomNotificationLog extends AbstractEntity
{
    protected Event $event;
    protected string $details = '';
    protected string $message = '';
    protected int $emailsSent = 0;
    protected ?DateTime $tstamp = null;
    protected int $cruserId = 0;

    public function setDetails(string $details)
    {
        $this->details = $details;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function setEvent(Event $event)
    {
        $this->event = $event;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEmailsSent(int $emailsSent)
    {
        $this->emailsSent = $emailsSent;
    }

    public function getEmailsSent(): int
    {
        return $this->emailsSent;
    }

    public function getTstamp(): ?DateTime
    {
        return $this->tstamp;
    }

    public function setTstamp(?DateTime $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    public function getCruserId(): int
    {
        return $this->cruserId;
    }

    public function setCruserId(int $cruserId)
    {
        $this->cruserId = $cruserId;
    }
}
