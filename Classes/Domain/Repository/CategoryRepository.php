<?php

declare(strict_types=1);

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
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class CategoryRepository extends Repository
{
    public function initializeObject(): void
    {
        $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * Returns all categories depending on the settings in the demand object
     */
    public function findDemanded(CategoryDemand $demand): QueryResultInterface
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
            $query->matching($query->logicalAnd(...$constraints));
        }

        if ($demand->getOrderField() !== '' && $demand->getOrderDirection() !== '' &&
            in_array($demand->getOrderField(), CategoryDemand::ORDER_FIELD_ALLOWED, true)
        ) {
            $orderings[$demand->getOrderField()] = ((strtolower($demand->getOrderDirection()) === 'desc') ?
                QueryInterface::ORDER_DESCENDING :
                QueryInterface::ORDER_ASCENDING);
            $query->setOrderings($orderings);
        }

        return $query->execute();
    }
}
