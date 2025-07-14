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
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event should be used to modify the sender data of aa admin message. Use the $type variable to distinguish
 * between the different types of messages
 */
final class ModifyAdminMessageSenderEvent
{
    public function __construct(
        private string $senderName,
        private string $senderEmail,
        private string $replyToEmail,
        private string $subject,
        private string $body,
        private readonly Registration $registration,
        private readonly int $type,
        private readonly NotificationService $notificationService,
        private readonly ServerRequestInterface $request
    ) {
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

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }

    public function setSenderName(string $senderName): void
    {
        $this->senderName = $senderName;
    }

    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    public function setReplyToEmail(string $replyToEmail): void
    {
        $this->replyToEmail = $replyToEmail;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
