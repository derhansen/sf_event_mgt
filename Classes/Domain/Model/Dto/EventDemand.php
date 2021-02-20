<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/**
 * Event demand
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
    protected $storagePage = '';

    /**
     * Current DateTime
     *
     * @var \DateTime
     */
    protected $currentDateTime;

    /**
     * Category
     *
     * @var string
     */
    protected $category;

    /**
     * Include subcategories
     *
     * @var bool
     */
    protected $includeSubcategories = false;

    /**
     * Category Conjunction
     *
     * @var string
     */
    protected $categoryConjunction = '';

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
     * Allowed order fields
     *
     * @var string
     */
    protected $orderFieldAllowed = '';

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
    protected $queryLimit;

    /**
     * Location
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Location
     */
    protected $location;

    /**
     * Speaker
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Speaker
     */
    protected $speaker;

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
     * Year
     *
     * @var int
     */
    protected $year;

    /**
     * Month
     *
     * @var int
     */
    protected $month;

    /**
     * Day
     *
     * @var int
     */
    protected $day;

    /**
     * Search Demand
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\SearchDemand
     */
    protected $searchDemand;

    /**
     * Organisator
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Model\Organisator
     */
    protected $organisator;

    /**
     * @var bool
     */
    protected $ignoreEnableFields = false;

    /**
     * @var string
     */
    protected $timeRestrictionLow;

    /**
     * @var string
     */
    protected $timeRestrictionHigh;

    /**
     * @var bool
     */
    protected $includeCurrent = false;

    /**
     * Sets the displayMode
     *
     * @param string $displayMode Displaymode
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

        return new \DateTime();
    }

    /**
     * Sets the category (seperated by comma)
     *
     * @param string $category Category
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
     */
    public function setOrderField($orderField)
    {
        $this->orderField = $orderField;
    }

    /**
     * Returns orderFieldAllowed
     *
     * @return string
     */
    public function getOrderFieldAllowed()
    {
        return $this->orderFieldAllowed;
    }

    /**
     * Sets orderFieldAllowed
     *
     * @param string $orderFieldAllowed
     */
    public function setOrderFieldAllowed($orderFieldAllowed)
    {
        $this->orderFieldAllowed = $orderFieldAllowed;
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
     */
    public function setLocationCountry($locationCountry)
    {
        $this->locationCountry = $locationCountry;
    }

    /**
     * Returns year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets year
     *
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Returns month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Sets month
     *
     * @param int $month
     */
    public function setMonth($month)
    {
        $this->month = $month;
    }

    /**
     * Returns day
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay($day)
    {
        $this->day = $day;
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
     */
    public function setSearchDemand($searchDemand)
    {
        $this->searchDemand = $searchDemand;
    }

    /**
     * Returns organisator
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Organisator
     */
    public function getOrganisator()
    {
        return $this->organisator;
    }

    /**
     * Sets organisator
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Organisator $organisator
     */
    public function setOrganisator($organisator)
    {
        $this->organisator = $organisator;
    }

    /**
     * Returns categoryConjuction
     *
     * @return string
     */
    public function getCategoryConjunction()
    {
        return $this->categoryConjunction;
    }

    /**
     * Sets categoryConjuction
     *
     * @param string $categoryConjunction
     */
    public function setCategoryConjunction($categoryConjunction)
    {
        $this->categoryConjunction = $categoryConjunction;
    }

    /**
     * Returns speaker
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Speaker
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * Sets speaker
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Speaker $speaker
     */
    public function setSpeaker($speaker)
    {
        $this->speaker = $speaker;
    }

    /**
     * @return bool
     */
    public function getIgnoreEnableFields(): bool
    {
        return $this->ignoreEnableFields;
    }

    /**
     * @param bool $ignoreEnableFields
     */
    public function setIgnoreEnableFields(bool $ignoreEnableFields): void
    {
        $this->ignoreEnableFields = $ignoreEnableFields;
    }

    /**
     * @return string
     */
    public function getTimeRestrictionLow(): ?string
    {
        return $this->timeRestrictionLow;
    }

    /**
     * @param string $timeRestrictionLow
     */
    public function setTimeRestrictionLow($timeRestrictionLow): void
    {
        $this->timeRestrictionLow = $timeRestrictionLow;
    }

    /**
     * @return string
     */
    public function getTimeRestrictionHigh(): ?string
    {
        return $this->timeRestrictionHigh;
    }

    /**
     * @param string $timeRestrictionHigh
     */
    public function setTimeRestrictionHigh($timeRestrictionHigh): void
    {
        $this->timeRestrictionHigh = $timeRestrictionHigh;
    }

    /**
     * @return bool
     */
    public function getIncludeCurrent(): bool
    {
        return $this->includeCurrent;
    }

    /**
     * @param bool $includeCurrent
     */
    public function setIncludeCurrent($includeCurrent): void
    {
        $this->includeCurrent = $includeCurrent;
    }
}
