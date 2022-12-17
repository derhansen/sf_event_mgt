<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryHelper;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageUtility
{
    /**
     * Find all ids from given comma separated list of PIDs and level
     */
    public static function extendPidListByChildren(string $pidList = '', int $recursive = 0): string
    {
        if ($recursive <= 0) {
            return $pidList;
        }

        $recursiveStoragePids = $pidList;
        $storagePids = GeneralUtility::intExplode(',', $pidList);
        foreach ($storagePids as $startPid) {
            $pids = self::getTreeList($startPid, $recursive);
            if (strlen($pids) > 0) {
                $recursiveStoragePids .= ',' . $pids;
            }
        }

        return $recursiveStoragePids;
    }

    /**
     * Recursively fetch all descendants of a given page
     */
    protected static function getTreeList(int $id, int $depth, int $begin = 0, string $permClause = ''): string
    {
        if ($id < 0) {
            $id = (int)abs($id);
        }

        if ($begin === 0) {
            $theList = $id;
        } else {
            $theList = '';
        }

        if ($id && $depth > 0) {
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
            $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $queryBuilder->select('uid')
                ->from('pages')
                ->where(
                    $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($id, Connection::PARAM_INT)),
                    $queryBuilder->expr()->eq('sys_language_uid', 0)
                )
                ->orderBy('uid');
            if ($permClause !== '') {
                $queryBuilder->andWhere(QueryHelper::stripLogicalOperatorPrefix($permClause));
            }
            $statement = $queryBuilder->executeQuery();
            while ($row = $statement->fetchAssociative()) {
                if ($begin <= 0) {
                    $theList .= ',' . $row['uid'];
                }
                if ($depth > 1) {
                    $theSubList = self::getTreeList((int)$row['uid'], $depth - 1, $begin - 1, $permClause);
                    if (!empty($theList) && !empty($theSubList) && ($theSubList[0] !== ',')) {
                        $theList .= ',';
                    }
                    $theList .= $theSubList;
                }
            }
        }

        return (string)$theList;
    }
}
