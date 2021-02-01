<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/**
 * Category demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    const ORDER_FIELD_ALLOWED = ['title', 'uid', 'sorting'];

    /**
     * Storage page
     *
     * @var string
     */
    protected $storagePage;

    /**
     * Restrict categories to storagePage
     *
     * @var bool
     */
    protected $restrictToStoragePage = false;

    /**
     * Categories (seperated by comma)
     *
     * @var string
     */
    protected $categories = '';

    /**
     * Include subcategories
     *
     * @var bool
     */
    protected $includeSubcategories = false;

    /**
     * @var string
     */
    protected $orderField = 'uid';

    /**
     * @var string
     */
    protected $orderDirection = 'asc';

    /**
     * Sets the storage page
     *
     * @param string $storagePage Storagepage
     */
    public function setStoragePage($storagePage)
    {
        $this->storagePage = $storagePage;
    }

    /**
     * Returns the storage page
     *
     * @return string
     */
    public function getStoragePage()
    {
        return $this->storagePage;
    }

    /**
     * Returns restrictToStoragePage
     *
     * @return bool
     */
    public function getRestrictToStoragePage()
    {
        return $this->restrictToStoragePage;
    }

    /**
     * Sets restrictToStoragePage
     *
     * @param bool $restrictToStoragePage
     */
    public function setRestrictToStoragePage($restrictToStoragePage)
    {
        $this->restrictToStoragePage = $restrictToStoragePage;
    }

    /**
     * Returns the categories
     *
     * @return string
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param string $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns includeSubcategories
     *
     * @return bool
     */
    public function getIncludeSubcategories()
    {
        return $this->includeSubcategories;
    }

    /**
     * Sets includeSubcategories
     *
     * @param bool $includeSubcategories
     */
    public function setIncludeSubcategories($includeSubcategories)
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
}
