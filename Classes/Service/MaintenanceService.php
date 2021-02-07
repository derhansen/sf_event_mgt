<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * MaintenanceService
 */
class MaintenanceService
{
    /**
     * Handles expired registration
     *
     * @param bool $delete
     */
    public function handleExpiredRegistrations(bool $delete = false)
    {
        $eventCacheService = GeneralUtility::makeInstance(EventCacheService::class);
        $registrationUids = $this->getExpiredRegistrations();
        foreach ($registrationUids as $registration) {
            $this->updateRegistration($registration['uid'], $delete);
            $eventCacheService->flushEventCache($registration['event'], $registration['pid']);
        }
    }

    /**
     * Updates the given registration
     *
     * @param int $registrationUid
     * @param bool $delete
     */
    protected function updateRegistration(int $registrationUid, bool $delete = false)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_sfeventmgt_domain_model_registration');

        $field = $delete === true ? 'deleted' : 'hidden';
        $updateFields = [];
        $updateFields[$field] = 1;

        $connection->update(
            'tx_sfeventmgt_domain_model_registration',
            $updateFields,
            ['uid' => $registrationUid]
        );
    }

    /**
     * Returns an array of registration uids, which are considered as expired
     *
     * @return array
     */
    protected function getExpiredRegistrations(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');

        return $queryBuilder
            ->select('uid', 'pid', 'event')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->where(
                $queryBuilder->expr()->lte(
                    'confirmation_until',
                    $queryBuilder->createNamedParameter(time(), \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'confirmed',
                    $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll();
    }
}
