<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CategoryService
 *
 * @author Georg Ringer <typo3@ringerge.org>
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryService
{
    /**
     * Returns the given categories including their subcategories
     *
     * @param string $categories
     * @return string
     */
    public static function getCategoryListWithChilds($categories)
    {
        return self::getChildrenCategoriesRecursive($categories);
    }

    /**
     * Get child categories
     *
     * @param string $idList list of category ids to start
     * @param int $counter
     * @return string comma separated list of category ids
     */
    private static function getChildrenCategoriesRecursive($idList, $counter = 0): string
    {
        $result = [];

        // add idlist to the output too
        if ($counter === 0) {
            $result[] = self::cleanIntList($idList);
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_category');
        $res = $queryBuilder
            ->select('uid')
            ->from('sys_category')
            ->where(
                $queryBuilder->expr()->in(
                    'parent',
                    $queryBuilder->createNamedParameter(
                        array_map('intval', explode(',', $idList)),
                        Connection::PARAM_INT_ARRAY
                    )
                )
            )
            ->execute();

        while (($row = $res->fetch())) {
            $counter++;
            if ($counter > 10000) {
                GeneralUtility::makeInstance(TimeTracker::class)
                    ->setTSlogMessage('EXT:sf_event_mgt: one or more recursive categories where found');

                return implode(',', $result);
            }
            $subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }

        $result = implode(',', $result);

        return $result;
    }

    /**
     * Clean list of integers
     *
     * @param string $list
     * @return string
     */
    private static function cleanIntList($list): string
    {
        return implode(',', GeneralUtility::intExplode(',', $list));
    }
}
