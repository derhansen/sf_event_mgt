<?php
namespace DERHANSEN\SfEventMgt\Domain\Repository;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * ForeignRecordRepository which respects the ForeignRecordDemandObject
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
abstract class AbstractForeignRecordRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Disable the use of storage records, because the StoragePage can be set
     * in the plugin
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * Returns all records depending on the settings in the demand object
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\ForeignRecordDemand $demand ForeignRecordDemand
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findDemanded($demand)
    {
        $constraints = [];
        $query = $this->createQuery();

        if ($demand->getRestrictForeignRecordsToStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }

}