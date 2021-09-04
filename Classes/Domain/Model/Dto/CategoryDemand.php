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

    /**
     * Sets the storage page
     *
     * @param string $storagePage Storagepage
     */
    public function setStoragePage(string $storagePage): void
    {
        $this->storagePage = $storagePage;
    }

    /**
     * Returns the storage page
     *
     * @return string
     */
    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    /**
     * Returns restrictToStoragePage
     *
     * @return bool
     */
    public function getRestrictToStoragePage(): bool
    {
        return $this->restrictToStoragePage;
    }

    /**
     * Sets restrictToStoragePage
     *
     * @param bool $restrictToStoragePage
     */
    public function setRestrictToStoragePage(bool $restrictToStoragePage): void
    {
        $this->restrictToStoragePage = $restrictToStoragePage;
    }

    /**
     * Returns the categories
     *
     * @return string
     */
    public function getCategories(): string
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param string $categories
     */
    public function setCategories(string $categories): void
    {
        $this->categories = $categories;
    }

    /**
     * Returns includeSubcategories
     *
     * @return bool
     */
    public function getIncludeSubcategories(): bool
    {
        return $this->includeSubcategories;
    }

    /**
     * Sets includeSubcategories
     *
     * @param bool $includeSubcategories
     */
    public function setIncludeSubcategories(bool $includeSubcategories)
    {
        $this->includeSubcategories = $includeSubcategories;
    }

    /**
     * @return string
     */
    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    /**
     * @param string $orderDirection
     */
    public function setOrderDirection(string $orderDirection): void
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * @return string
     */
    public function getOrderField(): string
    {
        return $this->orderField;
    }

    /**
     * @param string $orderField
     */
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
            PageUtility::extendPidListByChildren($settings['storagePage'] ?? '', $settings['recursive'] ?? 0)
        );
        $demand->setRestrictToStoragePage((bool)($settings['restrictForeignRecordsToStoragePage'] ?? false));
        $demand->setCategories($settings['categoryMenu']['categories'] ?? '');
        $demand->setIncludeSubcategories((bool)($settings['categoryMenu']['includeSubcategories'] ?? false));
        $demand->setOrderField($settings['categoryMenu']['orderField'] ?? 'uid');
        $demand->setOrderDirection($settings['categoryMenu']['orderDirection'] ?? 'asc');
        return $demand;
    }
}
