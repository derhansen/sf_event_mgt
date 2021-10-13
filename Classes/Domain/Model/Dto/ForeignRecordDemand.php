<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ForeignRecord demand
 */
class ForeignRecordDemand
{
    protected string $storagePage = '';
    protected bool $restrictForeignRecordsToStoragePage = false;

    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    public function setStoragePage(string $storagePage): void
    {
        $this->storagePage = $storagePage;
    }

    public function getRestrictForeignRecordsToStoragePage(): bool
    {
        return $this->restrictForeignRecordsToStoragePage;
    }

    public function setRestrictForeignRecordsToStoragePage(bool $restrictForeignRecordsToStoragePage): void
    {
        $this->restrictForeignRecordsToStoragePage = $restrictForeignRecordsToStoragePage;
    }

    /**
     * Creates a new ForeignRecordDemand object from the given settings.
     *
     * @param array $settings
     * @return ForeignRecordDemand
     */
    public static function createFromSettings(array $settings = []): self
    {
        $demand = GeneralUtility::makeInstance(ForeignRecordDemand::class);
        $demand->setStoragePage((string)($settings['storagePage'] ?? ''));
        $demand->setRestrictForeignRecordsToStoragePage(
            (bool)($settings['restrictForeignRecordsToStoragePage'] ?? false)
        );

        return $demand;
    }
}
