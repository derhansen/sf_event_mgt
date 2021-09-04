<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Utility\PageUtility;

/**
 * Category demand
 */
class CategoryDemand
{
    public const ORDER_FIELD_ALLOWED = ['title', 'uid', 'sorting'];

    protected string $storagePage = '';
    protected bool $restrictToStoragePage = false;
    protected string $categories = '';
    protected bool $includeSubcategories = false;
    protected string $orderField = 'uid';
    protected string $orderDirection = 'asc';

    public function setStoragePage(string $storagePage): void
    {
        $this->storagePage = $storagePage;
    }

    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    public function getRestrictToStoragePage(): bool
    {
        return $this->restrictToStoragePage;
    }

    public function setRestrictToStoragePage(bool $restrictToStoragePage): void
    {
        $this->restrictToStoragePage = $restrictToStoragePage;
    }

    public function getCategories(): string
    {
        return $this->categories;
    }

    public function setCategories(string $categories): void
    {
        $this->categories = $categories;
    }

    public function getIncludeSubcategories(): bool
    {
        return $this->includeSubcategories;
    }

    public function setIncludeSubcategories(bool $includeSubcategories)
    {
        $this->includeSubcategories = $includeSubcategories;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(string $orderDirection): void
    {
        $this->orderDirection = $orderDirection;
    }

    public function getOrderField(): string
    {
        return $this->orderField;
    }

    public function setOrderField(string $orderField): void
    {
        $this->orderField = $orderField;
    }

    /**
     * Creates a new CategoryDemand object from the given settings. Respects recursive setting for storage page
     * and extends all PIDs to children if set.
     *
     * @param array $settings
     * @return CategoryDemand
     */
    public static function createFromSettings(array $settings = []): CategoryDemand
    {
        $demand = new CategoryDemand();
        $demand->setStoragePage(
            PageUtility::extendPidListByChildren(
                (string)($settings['storagePage'] ?? ''),
                (int)($settings['recursive'] ?? 0)
            )
        );
        $demand->setRestrictToStoragePage((bool)($settings['restrictForeignRecordsToStoragePage'] ?? false));
        $demand->setCategories($settings['categoryMenu']['categories'] ?? '');
        $demand->setIncludeSubcategories((bool)($settings['categoryMenu']['includeSubcategories'] ?? false));
        $demand->setOrderField($settings['categoryMenu']['orderField'] ?? 'uid');
        $demand->setOrderDirection($settings['categoryMenu']['orderDirection'] ?? 'asc');
        return $demand;
    }
}
