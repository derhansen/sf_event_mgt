<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
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