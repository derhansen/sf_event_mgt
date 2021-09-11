<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\NotificationService;

/**
 * This event should be used to modify the attachments of a user message. Use the $type variable to distinguish
 * between the different types of messages
 */
final class ModifyUserMessageAttachmentsEvent
{
    private array $attachments;
    private Registration $registration;
    private int $type;
    private NotificationService $notificationService;

    public function __construct(
        array $attachments,
        Registration $registration,
        int $type,
        NotificationService $notificationService
    ) {
        $this->attachments = $attachments;
        $this->registration = $registration;
        $this->type = $type;
        $this->notificationService = $notificationService;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }

    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }
}
