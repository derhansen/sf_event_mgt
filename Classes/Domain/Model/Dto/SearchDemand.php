<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/**
 * Search demand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SearchDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Search string
     *
     * @var string
     */
    protected $search = '';

    /**
     * Search fields
     *
     * @var string
     */
    protected $fields;

    /**
     * StartDate
     *
     * @var \DateTime
     */
    protected $startDate;

    /**
     * EndDate
     *
     * @var \DateTime
     */
    protected $endDate;

    /**
     * Set the start date
     *
     * @param \DateTime $startDate StartDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Returns the start date
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set the end date
     *
     * @param \DateTime $endDate EndDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Get the end date
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Returns Search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * Sets search
     *
     * @param string $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }

    /**
     * Returns fields
     *
     * @return string
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Sets fields
     *
     * @param string $fields
     * @return void
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Returns if the demand object has at least one search property set
     *
     * @return bool
     */
    public function getHasQuery()
    {
        return $this->search !== '' || $this->startDate !== null || $this->endDate !== null;
    }
}
