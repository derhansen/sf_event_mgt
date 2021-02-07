<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * ForeignRecord demand
 */
class ForeignRecordDemand extends AbstractEntity
{
    /**
     * Storage page
     *
     * @var string
     */
    protected $storagePage;

    /**
     * Restrict foreign records to storagePage
     *
     * @var bool
     */
    protected $restrictForeignRecordsToStoragePage = false;

    /**
     * Sets the storage page
     *
     * @param string $storagePage Storagepage
     */
    public function setStoragePage($storagePage)
    {
        $this->storagePage = $storagePage;
    }

    /**
     * Returns the storage page
     *
     * @return string
     */
    public function getStoragePage()
    {
        return $this->storagePage;
    }

    /**
     * Returns restrictForeignRecordsToStoragePage
     *
     * @return bool
     */
    public function getRestrictForeignRecordsToStoragePage()
    {
        return $this->restrictForeignRecordsToStoragePage;
    }

    /**
     * Sets restrictForeignRecordsToStoragePage
     *
     * @param bool $restrictForeignRecordsToStoragePage
     */
    public function setRestrictForeignRecordsToStoragePage($restrictForeignRecordsToStoragePage)
    {
        $this->restrictForeignRecordsToStoragePage = $restrictForeignRecordsToStoragePage;
    }
}
