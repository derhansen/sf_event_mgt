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
    /**
     * @var array
     */
    private $attachments;

    /**
     * @var Registration
     */
    private $registration;

    /**
     * @var int
     */
    private $type;

    /**
     * @var NotificationService
     */
    private $notificationService;

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

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @return Registration
     */
    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return NotificationService
     */
    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments): void
    {
        $this->attachments = $attachments;
    }
}
