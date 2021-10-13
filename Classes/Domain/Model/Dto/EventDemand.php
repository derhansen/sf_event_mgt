<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use DERHANSEN\SfEventMgt\Domain\Model\Organisator;
use DERHANSEN\SfEventMgt\Domain\Model\Speaker;
use DERHANSEN\SfEventMgt\Utility\PageUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Event demand
 */
class EventDemand
{
    protected string $displayMode = 'all';
    protected string $storagePage = '';
    protected ?DateTime $currentDateTime = null;
    protected string $category = '';
    protected bool $includeSubcategories = false;
    protected string $categoryConjunction = '';
    protected int $topEventRestriction = 0;
    protected string $orderField = '';
    protected string $orderFieldAllowed = '';
    protected string $orderDirection = '';
    protected int $queryLimit = 0;
    protected string $locationCity = '';
    protected string $locationCountry = '';
    protected int $year = 0;
    protected int $month = 0;
    protected int $day = 0;
    protected ?SearchDemand $searchDemand = null;
    protected bool $ignoreEnableFields = false;
    protected string $timeRestrictionLow = '';
    protected string $timeRestrictionHigh = '';
    protected bool $includeCurrent = false;

    /**
     * Can be an object (if set by code/property mapper) or a string if set by settings array from plugin
     *
     * @var Location|string|null
     */
    protected $location;

    /**
     * Can be an object (if set by code/property mapper) or a string if set by settings array from plugin
     *
     * @var Speaker|string|null
     */
    protected $speaker;

    /**
     * Can be an object (if set by code/property mapper) or a string if set by settings array from plugin
     *
     * @var Organisator|string|null
     */
    protected $organisator;

    public function getDisplayMode(): string
    {
        return $this->displayMode;
    }

    public function setDisplayMode(string $displayMode): void
    {
        $this->displayMode = $displayMode;
    }

    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    public function setStoragePage(string $storagePage): void
    {
        $this->storagePage = $storagePage;
    }

    public function setCurrentDateTime(DateTime $currentDateTime): void
    {
        $this->currentDateTime = $currentDateTime;
    }

    public function getCurrentDateTime(): DateTime
    {
        return $this->currentDateTime ?? new \DateTime();
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getIncludeSubcategories(): bool
    {
        return $this->includeSubcategories;
    }

    public function setIncludeSubcategories(bool $includeSubcategories): void
    {
        $this->includeSubcategories = $includeSubcategories;
    }

    public function getTopEventRestriction(): int
    {
        return $this->topEventRestriction;
    }

    public function setTopEventRestriction(int $topEventRestriction): void
    {
        $this->topEventRestriction = $topEventRestriction;
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

    public function getOrderFieldAllowed(): string
    {
        return $this->orderFieldAllowed;
    }

    public function setOrderFieldAllowed(string $orderFieldAllowed): void
    {
        $this->orderFieldAllowed = $orderFieldAllowed;
    }

    public function getQueryLimit(): int
    {
        return $this->queryLimit;
    }

    public function setQueryLimit(int $queryLimit): void
    {
        $this->queryLimit = $queryLimit;
    }

    public function getLocationCity(): string
    {
        return $this->locationCity;
    }

    public function setLocationCity(string $locationCity): void
    {
        $this->locationCity = $locationCity;
    }

    public function getLocationCountry(): string
    {
        return $this->locationCountry;
    }

    public function setLocationCountry(string $locationCountry): void
    {
        $this->locationCountry = $locationCountry;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getSearchDemand(): ?SearchDemand
    {
        return $this->searchDemand;
    }

    public function setSearchDemand(?SearchDemand $searchDemand): void
    {
        $this->searchDemand = $searchDemand;
    }

    public function getCategoryConjunction(): string
    {
        return $this->categoryConjunction;
    }

    public function setCategoryConjunction(string $categoryConjunction): void
    {
        $this->categoryConjunction = $categoryConjunction;
    }

    public function getIgnoreEnableFields(): bool
    {
        return $this->ignoreEnableFields;
    }

    public function setIgnoreEnableFields(bool $ignoreEnableFields): void
    {
        $this->ignoreEnableFields = $ignoreEnableFields;
    }

    public function getTimeRestrictionLow(): string
    {
        return $this->timeRestrictionLow;
    }

    public function setTimeRestrictionLow(string $timeRestrictionLow): void
    {
        $this->timeRestrictionLow = $timeRestrictionLow;
    }

    public function getTimeRestrictionHigh(): string
    {
        return $this->timeRestrictionHigh;
    }

    public function setTimeRestrictionHigh(string $timeRestrictionHigh): void
    {
        $this->timeRestrictionHigh = $timeRestrictionHigh;
    }

    public function getIncludeCurrent(): bool
    {
        return $this->includeCurrent;
    }

    public function setIncludeCurrent(bool $includeCurrent): void
    {
        $this->includeCurrent = $includeCurrent;
    }

    /**
     * @return Location|string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param Location|string|null $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return Speaker|string|null
     */
    public function getSpeaker()
    {
        return $this->speaker;
    }

    /**
     * @param Speaker|string|null $speaker
     */
    public function setSpeaker($speaker): void
    {
        $this->speaker = $speaker;
    }

    /**
     * @return Organisator|string|null
     */
    public function getOrganisator()
    {
        return $this->organisator;
    }

    /**
     * @param Organisator|string|null $organisator
     */
    public function setOrganisator($organisator): void
    {
        $this->organisator = $organisator;
    }

    /**
     * Creates a new EventDemand object from the given settings. Respects recursive setting for storage page
     * and extends all PIDs to children if set.
     *
     * @param array $settings
     * @return EventDemand
     */
    public static function createFromSettings(array $settings = []): self
    {
        $demand = GeneralUtility::makeInstance(EventDemand::class);

        $demand->setDisplayMode($settings['displayMode'] ?? 'all');
        $demand->setStoragePage(
            PageUtility::extendPidListByChildren(
                (string)($settings['storagePage'] ?? ''),
                (int)($settings['recursive'] ?? 0)
            )
        );
        $demand->setCategoryConjunction($settings['categoryConjunction'] ?? '');
        $demand->setCategory($settings['category'] ?? '');
        $demand->setIncludeSubcategories((bool)($settings['includeSubcategories'] ?? false));
        $demand->setTopEventRestriction((int)($settings['topEventRestriction'] ?? 0));
        $demand->setOrderField($settings['orderField'] ?? '');
        $demand->setOrderFieldAllowed($settings['orderFieldAllowed'] ?? '');
        $demand->setOrderDirection($settings['orderDirection'] ?? '');
        $demand->setQueryLimit((int)($settings['queryLimit'] ?? 0));
        $demand->setTimeRestrictionLow($settings['timeRestrictionLow'] ?? '');
        $demand->setTimeRestrictionHigh($settings['timeRestrictionHigh'] ?? '');
        $demand->setIncludeCurrent((bool)($settings['includeCurrent'] ?? false));
        $demand->setLocation($settings['location'] ?? null);
        $demand->setOrganisator($settings['organisator'] ?? null);
        $demand->setSpeaker($settings['speaker'] ?? null);

        return $demand;
    }
}
