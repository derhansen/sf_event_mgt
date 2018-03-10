<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * Category demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CategoryDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
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
     * Sets the storage page
     *
     * @param string $storagePage Storagepage
     *
     * @return void
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
     *
     * @return void
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
     * @return void
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
     * @return void
     */
    public function setIncludeSubcategories($includeSubcategories)
    {
        $this->includeSubcategories = $includeSubcategories;
    }
}
