<?php
namespace DERHANSEN\SfEventMgt\Domain\Repository;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use DERHANSEN\SfEventMgt\Service\CategoryService;

/**
 * The repository for Categories
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Domain\Repository\CategoryRepository
{

    /**
     * Returns all categories depending on the settings in the demand object
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand $demand CategoryDamand
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findDemanded($demand)
    {
        $constraints = [];
        $query = $this->createQuery();

        if ($demand->getRestrictToStoragePage()) {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }

        if ($demand->getCategories()) {
            if ($demand->getIncludeSubcategories()) {
                $categoryList = CategoryService::getCategoryListWithChilds($demand->getCategories());
                $pidList = GeneralUtility::intExplode(',', $categoryList, true);
            } else {
                $pidList = GeneralUtility::intExplode(',', $demand->getCategories(), true);
            }
            $constraints[] = $query->in('uid', $pidList);
        }

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        return $query->execute();
    }

}
