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
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Event;

/**
 * This event is triggered before a custom notification log entry is saved
 */
final class ModifyCustomNotificationLogEvent
{
    public function __construct(
        private CustomNotificationLog $customNotificationLog,
        private readonly Event $event,
        private readonly string $details,
        private readonly CustomNotification $customNotification
    ) {
    }

    public function getCustomNotificationLog(): CustomNotificationLog
    {
        return $this->customNotificationLog;
    }

    public function setCustomNotificationLog(CustomNotificationLog $customNotificationLog): void
    {
        $this->customNotificationLog = $customNotificationLog;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getDetails(): string
    {
        return $this->details;
    }

    public function getCustomNotification(): CustomNotification
    {
        return $this->customNotification;
    }
}
