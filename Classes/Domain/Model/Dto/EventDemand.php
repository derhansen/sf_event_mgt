<?php
namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

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
 * Event demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * Display mode
     *
     * @var string
     */
    protected $displayMode = 'all';

    /**
     * Storage page
     *
     * @var string
     */
    protected $storagePage;

    /**
     * Current DateTime
     *
     * @var \DateTime
     */
    protected $currentDateTime = null;

    /**
     * Category
     *
     * @var string
     */
    protected $category;

    /**
     * Top event
     *
     * @var int
     */
    protected $topEventRestriction = 0;

    /**
     * Order field
     *
     * @var string
     */
    protected $orderField = '';

    /**
     * Order direction
     *
     * @var string
     */
    protected $orderDirection = '';

    /**
     * Query limit
     *
     * @var int
     */
    protected $queryLimit = null;

    /**
     * Location
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    protected $location = null;

    /**
     * City for location
     *
     * @var string
     */
    protected $locationCity = '';

    /**
     * Country for location
     *
     * @var string
     */
    protected $locationCountry = '';

    /**
     * Search Demand
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand
     */
    protected $searchDemand = null;

    /**
     * Sets the displayMode
     *
     * @param string $displayMode Displaymode
     *
     * @return void
     */
    public function setDisplayMode($displayMode)
    {
        $this->displayMode = $displayMode;
    }

    /**
     * Returns the displayMode
     *
     * @return string
     */
    public function getDisplayMode()
    {
        return $this->displayMode;
    }

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
     * Sets the current DateTime
     *
     * @param \DateTime $currentDateTime CurrentDateTime
     *
     * @return void
     */
    public function setCurrentDateTime(\DateTime $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    /**
     * Returns the current datetime
     *
     * @return \DateTime
     */
    public function getCurrentDateTime()
    {
        if ($this->currentDateTime != null) {
            return $this->currentDateTime;
        }
        return new \DateTime;
    }

    /**
     * Sets the category (seperated by comma)
     *
     * @param string $category Category
     *
     * @return void
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Returns the category (seperated by comma)
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Returns topEventRestriction
     *
     * @return int
     */
    public function getTopEventRestriction()
    {
        return $this->topEventRestriction;
    }

    /**
     * Sets topEventRestriction
     *
     * @param int $topEventRestriction TopEventRestriction
     *
     * @return void
     */
    public function setTopEventRestriction($topEventRestriction)
    {
        $this->topEventRestriction = $topEventRestriction;
    }

    /**
     * Returns the order direction
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * Sets the order direction
     *
     * @param string $orderDirection OrderDirection
     *
     * @return void
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * Returns the order field
     *
     * @return string
     */
    public function getOrderField()
    {
        return $this->orderField;
    }

    /**
     * Sets the order field
     *
     * @param string $orderField OrderField
     *
     * @return void
     */
    public function setOrderField($orderField)
    {
        $this->orderField = $orderField;
    }

    /**
     * Returns the query limit
     *
     * @return int
     */
    public function getQueryLimit()
    {
        return $this->queryLimit;
    }

    /**
     * Sets the query limit
     *
     * @param int $queryLimit QueryLimit
     *
     * @return void
     */
    public function setQueryLimit($queryLimit)
    {
        $this->queryLimit = $queryLimit;
    }

    /**
     * Returns the location
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Sets the location
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Location $location Location
     *
     * @return void
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * Returns locationCity
     *
     * @return string
     */
    public function getLocationCity()
    {
        return $this->locationCity;
    }

    /**
     * Sets locationCity
     *
     * @param string $locationCity LocationCity
     *
     * @return void
     */
    public function setLocationCity($locationCity)
    {
        $this->locationCity = $locationCity;
    }

    /**
     * Returns locationCountry
     *
     * @return string
     */
    public function getLocationCountry()
    {
        return $this->locationCountry;
    }

    /**
     * Sets locationCountry
     *
     * @param string $locationCountry LocationCountry
     *
     * @return void
     */
    public function setLocationCountry($locationCountry)
    {
        $this->locationCountry = $locationCountry;
    }

    /**
     * Returns the searchDemand
     *
     * @return SearchDemand
     */
    public function getSearchDemand()
    {
        return $this->searchDemand;
    }

    /**
     * Sets the searchDemand
     *
     * @param SearchDemand $searchDemand
     * @return void
     */
    public function setSearchDemand($searchDemand)
    {
        $this->searchDemand = $searchDemand;
    }

}