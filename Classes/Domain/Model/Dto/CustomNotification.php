<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/**
 * Custom notification DTO
 */
class CustomNotification
{
    public const RECIPIENTS_ALL = 0;
    public const RECIPIENTS_CONFIRMED = 1;
    public const RECIPIENTS_UNCONFIRMED = 2;

    protected string $template = '';
    protected int $recipients = self::RECIPIENTS_CONFIRMED;
    protected string $overwriteSubject = '';
    protected string $additionalMessage = '';

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function getAdditionalMessage(): string
    {
        return $this->additionalMessage;
    }

    public function setAdditionalMessage(string $additionalMessage): void
    {
        $this->additionalMessage = $additionalMessage;
    }

    public function getRecipients(): int
    {
        return $this->recipients;
    }

    public function setRecipients(int $recipients): void
    {
        $this->recipients = $recipients;
    }

    public function getOverwriteSubject(): string
    {
        return $this->overwriteSubject;
    }

    public function setOverwriteSubject(string $overwriteSubject): void
    {
        $this->overwriteSubject = $overwriteSubject;
    }
}
