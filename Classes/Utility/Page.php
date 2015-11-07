<?php
namespace DERHANSEN\SfEventMgt\Utility;

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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Page
 *
 * @author Georg Ringer <typo3@ringerge.org>
 */
class Page
{

    /**
     * Find all ids from given ids and level
     *
     * @param string $pidList comma separated list of ids
     * @param integer $recursive recursive levels
     * @return string comma separated list of ids
     */
    public static function extendPidListByChildren($pidList = '', $recursive = 0)
    {
        $recursive = (int)$recursive;

        if ($recursive <= 0) {
            return $pidList;
        }

        $queryGenerator = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Database\\QueryGenerator');
        $recursiveStoragePids = $pidList;
        $storagePids = GeneralUtility::intExplode(',', $pidList);
        foreach ($storagePids as $startPid) {
            $pids = $queryGenerator->getTreeList($startPid, $recursive, 0, 1);
            if (strlen($pids) > 0) {
                $recursiveStoragePids .= ',' . $pids;
            }
        }
        return $recursiveStoragePids;
    }
}