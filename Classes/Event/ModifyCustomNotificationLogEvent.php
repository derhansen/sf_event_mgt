<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\CustomNotificationLog;
use DERHANSEN\SfEventMgt\Domain\Model\Event;

/**
 * This event is triggered before a custom notification log entry is saved
 */
final class ModifyCustomNotificationLogEvent
{
    /**
     * @var CustomNotificationLog
     */
    protected $customNotificationLog;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var string
     */
    protected $details;

    public function __construct(CustomNotificationLog $customNotificationLog, Event $event, string $details)
    {
        $this->customNotificationLog = $customNotificationLog;
        $this->event = $event;
        $this->details = $details;
    }

    /**
     * @return CustomNotificationLog
     */
    public function getCustomNotificationLog(): CustomNotificationLog
    {
        return $this->customNotificationLog;
    }

    /**
     * @param CustomNotificationLog $customNotificationLog
     */
    public function setCustomNotificationLog(CustomNotificationLog $customNotificationLog): void
    {
        $this->customNotificationLog = $customNotificationLog;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }
}
