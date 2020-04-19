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
 * This event should be used to modify the sender data of a user message. Use the $type variable to distinguish
 * between the different types of messages
 */
final class ModifyUserMessageSenderEvent
{
    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $senderEmail;

    /**
     * @var string
     */
    private $replyToEmail;

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
        string $senderName,
        string $senderEmail,
        string $replyToEmail,
        Registration $registration,
        int $type,
        NotificationService $notificationService
    ) {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->replyToEmail = $replyToEmail;
        $this->registration = $registration;
        $this->type = $type;
        $this->notificationService = $notificationService;
    }

    /**
     * @return string
     */
    public function getSenderName(): string
    {
        return $this->senderName;
    }

    /**
     * @return string
     */
    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    /**
     * @return string
     */
    public function getReplyToEmail(): string
    {
        return $this->replyToEmail;
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
     * @param string $senderName
     */
    public function setSenderName(string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @param string $senderEmail
     */
    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @param string $replyToEmail
     */
    public function setReplyToEmail(string $replyToEmail): void
    {
        $this->replyToEmail = $replyToEmail;
    }
}
