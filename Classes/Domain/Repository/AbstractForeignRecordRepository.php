<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * ForeignRecordRepository which respects the ForeignRecordDemandObject
 */
abstract class AbstractForeignRecordRepository extends Repository
{
    /**
     * Disable the use of storage records, because the StoragePage can be set
     * in the plugin
     */
    public function initializeObject(): void
    {
        $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * Returns all records depending on the settings in the demand object
     *
     * @return array|QueryResultInterface
     */
    public function findDemanded(ForeignRecordDemand $demand)
    {
        $constraints = [];
        $query = $this->createQuery();

        if ($demand->getRestrictForeignRecordsToStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        return $query->execute();
    }
}
