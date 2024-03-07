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
 * This event is triggered after a user message has been sent
 */
final class AfterUserMessageSentEvent
{
    public function __construct(
        private readonly Registration $registration,
        private readonly string $body,
        private readonly string $subject,
        private readonly array $attachments,
        private readonly string $senderName,
        private readonly string $senderEmail,
        private readonly string $replyToEmail,
        private readonly NotificationService $notificationService
    ) {
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getSenderName(): string
    {
        return $this->senderName;
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function getReplyToEmail(): string
    {
        return $this->replyToEmail;
    }

    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }
}
