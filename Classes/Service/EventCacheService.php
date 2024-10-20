<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3\CMS\Core\Cache\CacheDataCollectorInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventCacheService
{
    /**
     * Adds cache tags to page cache by event records.
     *
     * Following cache tags will be added to CacheDataCollector
     * "tx_sfeventmgt_uid_[event:uid]"
     */
    public function addCacheTagsByEventRecords(
        CacheDataCollectorInterface $cacheDataCollector,
        array $eventRecords
    ): void {
        $cacheTags = [];

        $now = new DateTime();

        /** @var Event $event */
        foreach ($eventRecords as $event) {
            // cache tag for each event record
            $cacheTags[] = new CacheTag('tx_sfeventmgt_uid_' . $event->getUid(), $event->getCacheTagLifetime($now));
        }
        if (count($cacheTags) > 0) {
            $cacheDataCollector->addCacheTags(...$cacheTags);
        }
    }

    /**
     * Adds page cache tags by used storagePages.
     *
     * This adds tags with the scheme tx_sfeventmgt_pid_[event:pid]
     */
    public function addPageCacheTagsByEventDemandObject(
        CacheDataCollectorInterface $cacheDataCollector,
        EventDemand $demand
    ): void {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageUid) {
                $lifetime = $this->getCacheTagLifetimeForStoragePage((int)$pageUid);
                $cacheTags[] = new CacheTag('tx_sfeventmgt_pid_' . $pageUid, $lifetime);
            }
        }
        if (count($cacheTags) > 0) {
            $cacheDataCollector->addCacheTags(...$cacheTags);
        }
    }

    /**
     * Flushes the page cache by event tags for the given event uid and pid
     */
    public function flushEventCache(int $eventUid = 0, int $eventPid = 0): void
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheTagsToFlush = [];

        if ($eventUid > 0) {
            $cacheTagsToFlush[] = 'tx_sfeventmgt_uid_' . $eventUid;
        }
        if ($eventPid > 0) {
            $cacheTagsToFlush[] = 'tx_sfeventmgt_pid_' . $eventPid;
        }

        foreach ($cacheTagsToFlush as $cacheTagToFlush) {
            $cacheManager->flushCachesInGroupByTag('pages', $cacheTagToFlush);
        }
    }

    /**
     * Calculates the cache tag liftime for all events in the given Page UID by considering the event several
     * event fields.
     */
    protected function getCacheTagLifetimeForStoragePage(int $pid): int
    {
        $result = PHP_INT_MAX;

        $currentTimestamp = (new DateTime())->getTimestamp();

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_event');
        $queryBuilder->getRestrictions()
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class);
        $timeFields = ['registration_startdate', 'registration_deadline', 'startdate', 'starttime', 'endtime'];
        $timeConditions = $queryBuilder->expr()->or();
        foreach ($timeFields as $field) {
            $queryBuilder->addSelectLiteral(
                'MIN('
                . 'CASE WHEN '
                . $queryBuilder->expr()->lte(
                    $field,
                    $queryBuilder->createNamedParameter($currentTimestamp, Connection::PARAM_INT)
                )
                . ' THEN NULL ELSE ' . $queryBuilder->quoteIdentifier($field) . ' END'
                . ') AS ' . $queryBuilder->quoteIdentifier($field)
            );
            $timeConditions->with(
                $queryBuilder->expr()->gt(
                    $field,
                    $queryBuilder->createNamedParameter($currentTimestamp, Connection::PARAM_INT)
                )
            );
        }

        // Only consider events where registration is enabled and that have not started yet.
        // Also include PID and timeConditions
        $row = $queryBuilder
            ->from('tx_sfeventmgt_domain_model_event')
            ->where(
                $queryBuilder->expr()->eq(
                    'pid',
                    $queryBuilder->createNamedParameter($pid, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->eq(
                    'enable_registration',
                    $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)
                ),
                $queryBuilder->expr()->gt(
                    'startdate',
                    $queryBuilder->createNamedParameter($currentTimestamp, Connection::PARAM_INT)
                ),
                $timeConditions
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($row) {
            foreach ($timeFields as $timeField) {
                if ($row[$timeField] !== null && (int)$row[$timeField] > $currentTimestamp) {
                    $result = min($result, (int)$row[$timeField]);
                }
            }
        }

        if ($result !== PHP_INT_MAX) {
            $result = $result - $currentTimestamp + 1;
        }

        return $result;
    }
}
