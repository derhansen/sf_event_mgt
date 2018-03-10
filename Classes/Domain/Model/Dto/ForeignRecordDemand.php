<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * ForeignRecord demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class ForeignRecordDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     *
     * @return void
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
     *
     * @return void
     */
    public function setRestrictForeignRecordsToStoragePage($restrictForeignRecordsToStoragePage)
    {
        $this->restrictForeignRecordsToStoragePage = $restrictForeignRecordsToStoragePage;
    }
}
