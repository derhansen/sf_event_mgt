<?php

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
class CustomNotification extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    public const RECIPIENTS_ALL = 0;
    public const RECIPIENTS_CONFIRMED = 1;
    public const RECIPIENTS_UNCONFIRMED = 2;

    /**
     * @var string
     */
    protected $template = '';

    /**
     * @var int
     */
    protected $recipients = self::RECIPIENTS_CONFIRMED;

    /**
     * @var string
     */
    protected $overwriteSubject = '';

    /**
     * @var string
     */
    protected $additionalMessage = '';

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getAdditionalMessage(): string
    {
        return $this->additionalMessage;
    }

    /**
     * @param string $additionalMessage
     */
    public function setAdditionalMessage(string $additionalMessage): void
    {
        $this->additionalMessage = $additionalMessage;
    }

    /**
     * @return int
     */
    public function getRecipients(): int
    {
        return $this->recipients;
    }

    /**
     * @param int $recipients
     */
    public function setRecipients(int $recipients): void
    {
        $this->recipients = $recipients;
    }

    /**
     * @return string
     */
    public function getOverwriteSubject(): string
    {
        return $this->overwriteSubject;
    }

    /**
     * @param string $overwriteSubject
     */
    public function setOverwriteSubject(string $overwriteSubject): void
    {
        $this->overwriteSubject = $overwriteSubject;
    }
}
