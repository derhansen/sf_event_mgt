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
    protected $startDate = null;

    /**
     * EndDate
     *
     * @var \DateTime
     */
    protected $endDate = null;

    /**
     * Set the start date
     *
     * @param \DateTime $startDate StartDate
     *
     * @return void
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
     *
     * @return void
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
     * @return void
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
     * @return string
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