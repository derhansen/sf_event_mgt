<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * MaintenanceService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class MaintenanceService
{
    /**
     * Handles expired registration
     *
     * @param bool $delete
     * @return void
     */
    public function handleExpiredRegistrations(bool $delete = false)
    {
        $eventCacheService = GeneralUtility::makeInstance(EventCacheService::class);
        $registrationUids = $this->getExpiredRegistrations();
        foreach ($registrationUids as $registration) {
            $this->updateRegistration((int)$registration['uid'], $delete);
            $eventCacheService->flushEventCache((int)$registration['event'], (int)$registration['pid']);
        }
    }

    /**
     * Updates the given registration
     *
     * @param int $registrationUid
     * @param bool $delete
     *
     * @return void
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
    protected function getExpiredRegistrations()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');

        $result = $queryBuilder
            ->select('uid')
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

        return $result;
    }
}
