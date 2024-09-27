<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Category;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Get usage count
 *
 * Example usage
 * {e:category.count(categoryUid:category.uid) -> f:variable(name: 'categoryUsageCount')}
 * {categoryUsageCount}
 */
class CountViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('categoryUid', 'int', 'Uid of the category', true);
    }

    public function render(): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_event');

        $categoryUid = $this->arguments['categoryUid'];
        $languageUid = GeneralUtility::makeInstance(Context::class)->getAspect('language')->getId();

        return $queryBuilder
            ->count('tx_sfeventmgt_domain_model_event.title')
            ->from('tx_sfeventmgt_domain_model_event')
            ->rightJoin(
                'tx_sfeventmgt_domain_model_event',
                'sys_category_record_mm',
                'sys_category_record_mm',
                $queryBuilder->expr()->eq('tx_sfeventmgt_domain_model_event.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_foreign'))
            )
            ->rightJoin(
                'sys_category_record_mm',
                'sys_category',
                'sys_category',
                $queryBuilder->expr()->eq('sys_category.uid', $queryBuilder->quoteIdentifier('sys_category_record_mm.uid_local'))
            )
            ->where(
                $queryBuilder->expr()->and(
                    $queryBuilder->expr()->eq(
                        'sys_category.uid',
                        $queryBuilder->createNamedParameter($categoryUid, Connection::PARAM_INT)
                    ),
                    $queryBuilder->expr()->eq(
                        'sys_category_record_mm.tablenames',
                        $queryBuilder->createNamedParameter('tx_sfeventmgt_domain_model_event')
                    ),
                    $queryBuilder->expr()->eq(
                        'sys_category_record_mm.fieldname',
                        $queryBuilder->createNamedParameter('category')
                    ),
                    $queryBuilder->expr()->in(
                        'tx_sfeventmgt_domain_model_event.sys_language_uid',
                        $queryBuilder->createNamedParameter([-1, $languageUid], Connection::PARAM_INT_ARRAY)
                    )
                )
            )
            ->executeQuery()
            ->fetchOne();
    }
}
