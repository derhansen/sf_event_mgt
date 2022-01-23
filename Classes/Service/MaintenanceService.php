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
    public function handleExpiredRegistrations(bool $delete = false): void
    {
        $eventCacheService = GeneralUtility::makeInstance(EventCacheService::class);
        $registrationUids = $this->getExpiredRegistrations();
        foreach ($registrationUids as $registration) {
            $this->updateRegistration($registration['uid'], $delete);
            $eventCacheService->flushEventCache($registration['event'], $registration['pid']);
        }
    }

    /**
     * Processes a GDPR cleaup by removing all registrations of expired events. Returns the amount of registrations
     * removed.
     *
     * @param int $days
     * @param bool $softDelete
     * @param bool $ignoreEventRestriction
     * @return int
     */
    public function processGdprCleanup(int $days, bool $softDelete, bool $ignoreEventRestriction): int
    {
        if (!$ignoreEventRestriction) {
            $registrations = $this->getGdprCleanupRegistrations($days);
        } else {
            $registrations = $this->getAllRegistrations();
        }

        foreach ($registrations as $registration) {
            if (!$softDelete) {
                $this->deleteRegistration($registration['uid']);
                $this->deleteRegistrationFieldValues($registration['uid']);
            } else {
                $this->updateRegistration($registration['uid'], true);
                $this->flagRegistrationFieldValuesAsDeleted($registration['uid']);
            }
        }

        return count($registrations);
    }

    /**
     * Updates the given registration
     *
     * @param int $registrationUid
     * @param bool $delete
     */
    protected function updateRegistration(int $registrationUid, bool $delete = false): void
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
     * Flags all registration field values for the given registration UID as deleted
     *
     * @param int $registrationUid
     */
    protected function flagRegistrationFieldValuesAsDeleted(int $registrationUid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_sfeventmgt_domain_model_registration_fieldvalue');

        $connection->update(
            'tx_sfeventmgt_domain_model_registration_fieldvalue',
            ['deleted' => 1],
            ['registration' => $registrationUid]
        );
    }

    /**
     * Deletes the registration with the given uid
     *
     * @param int $registrationUid
     * @return void
     */
    protected function deleteRegistration(int $registrationUid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_sfeventmgt_domain_model_registration');

        $connection->delete(
            'tx_sfeventmgt_domain_model_registration',
            ['uid' => $registrationUid]
        );
    }

    /**
     * Deletes all registration field values for the given registrationUid
     *
     * @param int $registrationUid
     * @return void
     */
    protected function deleteRegistrationFieldValues(int $registrationUid): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_sfeventmgt_domain_model_registration_fieldvalue');

        $connection->delete(
            'tx_sfeventmgt_domain_model_registration_fieldvalue',
            ['registration' => $registrationUid]
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

    /**
     * Returns all registrations, where the related event has expired based on the given amount of days
     *
     * @param int $days
     * @return array
     */
    protected function getGdprCleanupRegistrations(int $days): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->getRestrictions()->removeAll();
        $maxEndDate = (new \DateTime())->modify('-' . $days . ' days');

        return $queryBuilder
            ->select('tx_sfeventmgt_domain_model_registration.uid')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->join(
                'tx_sfeventmgt_domain_model_registration',
                'tx_sfeventmgt_domain_model_event',
                'e',
                $queryBuilder->expr()->eq(
                    'e.uid',
                    $queryBuilder->quoteIdentifier('tx_sfeventmgt_domain_model_registration.event')
                )
            )->where(
                $queryBuilder->expr()->lte(
                    'e.enddate',
                    $queryBuilder->createNamedParameter($maxEndDate->getTimestamp(), \PDO::PARAM_INT)
                )
            )->execute()
            ->fetchAll();
    }

    /**
     * Returns all registrations including hidden and deleted
     *
     * @return array
     */
    protected function getAllRegistrations(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder
            ->select('uid')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->execute()
            ->fetchAll();
    }
}
