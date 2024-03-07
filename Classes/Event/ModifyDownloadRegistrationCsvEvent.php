<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

/**
 * This event is triggered before the registration CSV download is initiated. Listerners can use this event
 * to set the CSV content and the CSV filename
 */
final class ModifyDownloadRegistrationCsvEvent
{
    public function __construct(
        protected string $csvContent,
        protected string $downloadFilename,
        protected readonly int $eventUid,
        protected readonly array $settings
    ) {
    }

    public function getCsvContent(): string
    {
        return $this->csvContent;
    }

    public function setCsvContent(string $csvContent): void
    {
        $this->csvContent = $csvContent;
    }

    public function getDownloadFilename(): string
    {
        return $this->downloadFilename;
    }

    public function setDownloadFilename(string $downloadFilename): void
    {
        $this->downloadFilename = $downloadFilename;
    }

    public function getEventUid(): int
    {
        return $this->eventUid;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }
}
