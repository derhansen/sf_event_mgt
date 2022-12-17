<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Hooks;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * PageCache class which implementes the TYPO3 Core hook:
 * $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['get_cache_timeout']
 */
class PageCache
{
    /**
     * Calculates the page cache timeout for configured pages with event records. Pages must be configured in TypoScript
     * using cache.config (similar to starttime/endtime cache handling)
     *
     * Example: config.cache.3 = tx_sfeventmgt_domain_model_event:2
     *
     * The cache for PID 3 will respect registration_startdate/registration_deadline of event record in PID 2
     *
     * @return int|mixed
     */
    public function getCacheTimeout(array $params, TypoScriptFrontendController $pObj)
    {
        $eventBasedCacheTimeout = $this->calculatePageCacheTimeout($pObj);
        if ($eventBasedCacheTimeout === PHP_INT_MAX || $eventBasedCacheTimeout >= $params['cacheTimeout']) {
            // Return previous calculated timeout, since event based cache timeout is either not determined or larger
            return $params['cacheTimeout'];
        }
        return $eventBasedCacheTimeout;
    }

    /**
     * Calculates page cache timeout according to the events with registration_startdate/registration_deadline
     * on the page.
     *
     * Nearly similar to TypoScriptFrontendController::calculatePageCacheTimeout()
     *
     * @return int Page cache timeout or PHP_INT_MAX if cannot be determined
     */
    protected function calculatePageCacheTimeout(TypoScriptFrontendController $pObj): int
    {
        $result = PHP_INT_MAX;
        $tablesToConsider = $this->getCurrentPageCacheConfiguration($pObj);

        if (empty($tablesToConsider)) {
            return $result;
        }

        $now = $GLOBALS['ACCESS_TIME'];

        foreach ($tablesToConsider as $tableDef) {
            $result = min($result, $this->getFirstTimeValueForEvent($tableDef, $now));
        }

        return $result === PHP_INT_MAX ? PHP_INT_MAX : $result - $now + 1;
    }

    /**
     * Nearly similar to TypoScriptFrontendController::getCurrentPageCacheConfiguration, but only returns
     * entries that are relevant for sf_event_mgt
     */
    protected function getCurrentPageCacheConfiguration(TypoScriptFrontendController $pObj): array
    {
        // @extensionScannerIgnoreLine
        $id = $pObj->id;

        $tables = ['tt_content:' . $id];
        if (isset($pObj->config['config']['cache.'][$id])) {
            $cacheConfig = str_replace(':current', ':' . $id, $pObj->config['config']['cache.'][$id]);
            $tables = array_merge($tables, GeneralUtility::trimExplode(',', $cacheConfig));
        }
        if (isset($pObj->config['config']['cache.']['all'])) {
            $cacheConfig = str_replace(':current', ':' . $id, $pObj->config['config']['cache.']['all']);
            $tables = array_merge($tables, GeneralUtility::trimExplode(',', $cacheConfig));
        }
        $tables = array_unique($tables);

        $result = [];
        foreach ($tables as $table) {
            if (str_contains($table, 'tx_sfeventmgt_domain_model_event')) {
                $result[] = $table;
            }
        }

        return $result;
    }

    /**
     * Nearly similar to TypoScriptFrontendController::getFirstTimeValueForRecord but only considers event fields
     * registration_startdate and registration_deadline.
     *
     * @param string $tableDef Table definition (format tablename:pid)
     * @param int $now "Now" time value
     * @return int Value of the next registration_startdate/registration_deadline time or PHP_INT_MAX if not found
     */
    protected function getFirstTimeValueForEvent(string $tableDef, int $now): int
    {
        $result = PHP_INT_MAX;
        [$tableName, $pid] = GeneralUtility::trimExplode(':', $tableDef);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($tableName);
        $queryBuilder->getRestrictions()
            ->removeByType(StartTimeRestriction::class)
            ->removeByType(EndTimeRestriction::class);
        $timeFields = ['registration_startdate', 'registration_deadline'];
        $timeConditions = $queryBuilder->expr()->orX();
        foreach ($timeFields as $field) {
            $queryBuilder->addSelectLiteral(
                'MIN('
                . 'CASE WHEN '
                . $queryBuilder->expr()->lte(
                    $field,
                    $queryBuilder->createNamedParameter($now, Connection::PARAM_INT)
                )
                . ' THEN NULL ELSE ' . $queryBuilder->quoteIdentifier($field) . ' END'
                . ') AS ' . $queryBuilder->quoteIdentifier($field)
            );
            $timeConditions->add(
                $queryBuilder->expr()->gt(
                    $field,
                    $queryBuilder->createNamedParameter($now, Connection::PARAM_INT)
                )
            );
        }

        // Only consider events where registration is enabled and that have not started yet.
        // Also include PID and timeConditions
        $row = $queryBuilder
            ->from($tableName)
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
                    $queryBuilder->createNamedParameter($now, Connection::PARAM_INT)
                ),
                $timeConditions
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($row) {
            foreach ($timeFields as $timeField) {
                if ($row[$timeField] !== null && (int)$row[$timeField] > $now) {
                    $result = min($result, (int)$row[$timeField]);
                }
            }
        }

        return $result;
    }
}
