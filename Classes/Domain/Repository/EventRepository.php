<?php
namespace DERHANSEN\SfEventMgt\Domain\Repository;

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

use \TYPO3\CMS\Core\Utility\MathUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

/**
 * The repository for Events
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = array(
        'startdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    );

    /**
     * Disable the use of storage records, because the StoragePage can be set
     * in the plugin
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * Returns the objects of this repository matching the given demand
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface QueryResultInterface
     */
    public function findDemanded(EventDemand $eventDemand)
    {
        $constraints = array();
        $query = $this->createQuery();
        $this->setStoragePageConstraint($query, $eventDemand, $constraints);
        $this->setDisplayModeConstraint($query, $eventDemand, $constraints);
        $this->setCategoryConstraint($query, $eventDemand, $constraints);
        $this->setLocationConstraint($query, $eventDemand, $constraints);
        $this->setLocationCityConstraint($query, $eventDemand, $constraints);
        $this->setLocationCountryConstraint($query, $eventDemand, $constraints);
        $this->setStartEndDateConstraint($query, $eventDemand, $constraints);
        $this->setSearchConstraint($query, $eventDemand, $constraints);
        $this->setTopEventConstraint($query, $eventDemand, $constraints);
        $this->setOrderingsFromDemand($query, $eventDemand);

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd($constraints));
        }

        $this->setQueryLimitFromDemand($query, $eventDemand);
        return $query->execute();
    }

    /**
     * Sets a query limit to the given query for the given demand
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     *
     * @return void
     */
    protected function setQueryLimitFromDemand($query, EventDemand $eventDemand)
    {
        if ($eventDemand->getQueryLimit() != null &&
            MathUtility::canBeInterpretedAsInteger($eventDemand->getQueryLimit()) &&
            (int)$eventDemand->getQueryLimit() > 0
        ) {
            $query->setLimit((int)$eventDemand->getQueryLimit());
        }
    }

    /**
     * Sets the ordering to the given query for the given demand
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     *
     * @return void
     */
    protected function setOrderingsFromDemand($query, EventDemand $eventDemand)
    {
        $orderings = array();
        if ($eventDemand->getOrderField() != '' && $eventDemand->getOrderDirection() != '') {
            $orderings[$eventDemand->getOrderField()] = ((strtolower($eventDemand->getOrderDirection()) == 'desc') ?
                \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING :
                \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);
            $query->setOrderings($orderings);
        }
    }

    /**
     * Sets the storagePage constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setStoragePageConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getStoragePage() != '') {
            $pidList = GeneralUtility::intExplode(',', $eventDemand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }
    }

    /**
     * Sets the displayMode constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setDisplayModeConstraint($query, $eventDemand, &$constraints)
    {
        switch ($eventDemand->getDisplayMode()) {
            case 'future':
                $constraints[] = $query->greaterThan('startdate', $eventDemand->getCurrentDateTime());
                break;
            case 'past':
                $constraints[] = $query->lessThanOrEqual('enddate', $eventDemand->getCurrentDateTime());
                break;
            default:
        }
    }

    /**
     * Sets the category constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setCategoryConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getCategory() != '') {
            $categoryConstraints = array();
            $categories = GeneralUtility::intExplode(',', $eventDemand->getCategory(), true);
            foreach ($categories as $category) {
                $categoryConstraints[] = $query->contains('category', $category);
            }
            if (count($categoryConstraints) > 0) {
                $constraints[] = $query->logicalOr($categoryConstraints);
            }
        }
    }

    /**
     * Sets the location constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setLocationConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocation() !== null && $eventDemand->getLocation() != '') {
            $constraints[] = $query->equals('location', $eventDemand->getLocation());
        }
    }

    /**
     * Sets the location.city constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setLocationCityConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocationCity() !== null && $eventDemand->getLocationCity() != '') {
            $constraints[] = $query->equals('location.city', $eventDemand->getLocationCity());
        }
    }

    /**
     * Sets the location.country constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setLocationCountryConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocationCountry() !== null && $eventDemand->getLocationCountry() != '') {
            $constraints[] = $query->equals('location.country', $eventDemand->getLocationCountry());
        }
    }

    /**
     * Sets the start- and enddate constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setStartEndDateConstraint($query, $eventDemand, &$constraints)
    {
        /* StartDate */
        if ($eventDemand->getSearchDemand() && $eventDemand->getSearchDemand()->getStartDate() !== null) {
            $constraints[] = $query->greaterThanOrEqual('startdate', $eventDemand->getSearchDemand()->getStartDate());
        }

        /* EndDate */
        if ($eventDemand->getSearchDemand() && $eventDemand->getSearchDemand()->getEndDate() !== null) {
            $constraints[] = $query->lessThanOrEqual('enddate', $eventDemand->getSearchDemand()->getEndDate());
        }
    }

    /**
     * Sets the search constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setSearchConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getSearchDemand() &&
            $eventDemand->getSearchDemand()->getSearch() !== null &&
            $eventDemand->getSearchDemand()->getSearch() !== ''
        ) {
            $searchFields = GeneralUtility::trimExplode(',', $eventDemand->getSearchDemand()->getFields(), true);
            $searchConstraints = [];

            if (count($searchFields) === 0) {
                throw new \UnexpectedValueException('No search fields defined', 1318497755);
            }

            $searchSubject = $eventDemand->getSearchDemand()->getSearch();
            foreach ($searchFields as $field) {
                if (!empty($searchSubject)) {
                    $searchConstraints[] = $query->like($field, '%' . $searchSubject . '%', false);
                }
            }

            if (count($searchConstraints)) {
                $constraints[] = $query->logicalOr($searchConstraints);
            }
        }
    }

    /**
     * Sets the topEvent constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     *
     * @return void
     */
    protected function setTopEventConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getTopEventRestriction() > 0) {
            $constraints[] = $query->equals('topEvent', (bool)($eventDemand->getTopEventRestriction() - 1));
        }
    }

}