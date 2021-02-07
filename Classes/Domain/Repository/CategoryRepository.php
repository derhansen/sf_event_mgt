<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CategoryDemand;
use DERHANSEN\SfEventMgt\Service\CategoryService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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

        if ($demand->getCategories() !== null && $demand->getCategories() !== '') {
            $query->getQuerySettings()->setRespectSysLanguage(false);
        }

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

        if ($demand->getOrderField() !== '' && $demand->getOrderDirection() !== '' &&
            in_array($demand->getOrderField(), CategoryDemand::ORDER_FIELD_ALLOWED, true)
        ) {
            $orderings[$demand->getOrderField()] = ((strtolower($demand->getOrderDirection()) == 'desc') ?
                QueryInterface::ORDER_DESCENDING :
                QueryInterface::ORDER_ASCENDING);
            $query->setOrderings($orderings);
        }

        return $query->execute();
    }
}
