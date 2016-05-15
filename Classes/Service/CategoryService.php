<?php
namespace DERHANSEN\SfEventMgt\Service;

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
     * @param string $categoryList list of category ids to start
     * @param int $counter
     * @return string comma separated list of category ids
     */
    private function getChildrenCategoriesRecursive($categoryList, $counter = 0)
    {
        $result = [];

        // add idlist to the output too
        if ($counter === 0) {
            $result[] = $GLOBALS['TYPO3_DB']->cleanIntList($categoryList);
        }

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'uid',
            'sys_category',
            'sys_category.parent IN (' . $GLOBALS['TYPO3_DB']->cleanIntList($categoryList) . ') AND deleted=0'
        );

        while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
            $counter++;
            if ($counter > 10000) {
                $GLOBALS['TT']->setTSlogMessage('EXT:sf_event_mgt: one or more recursive categories where found');
                return implode(',', $result);
            }
            $subcategories = self::getChildrenCategoriesRecursive($row['uid'], $counter);
            $result[] = $row['uid'] . ($subcategories ? ',' . $subcategories : '');
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        $result = implode(',', $result);
        return $result;
    }
}