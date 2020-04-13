<?php
declare(strict_types = 1);
namespace DERHANSEN\SfEventMgt\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\NotificationService;

/**
 * This event is triggered after a admin message has been sent
 */
final class AfterAdminMessageSentEvent
{
    /**
     * @var Registration
     */
    private $registration;

    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $senderEmail;

    /**
     * @var NotificationService
     */
    private $notificationService;

    public function __construct(
        Registration $registration,
        string $body,
        string $subject,
        array $attachments,
        string $senderName,
        string $senderEmail,
        NotificationService $notificationService
    ) {
        $this->registration = $registration;
        $this->body = $body;
        $this->subject = $subject;
        $this->attachments = $attachments;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->notificationService = $notificationService;
    }

    /**
     * @return Registration
     */
    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
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
     * @return NotificationService
     */
    public function getNotificationService(): NotificationService
    {
        return $this->notificationService;
    }
}
