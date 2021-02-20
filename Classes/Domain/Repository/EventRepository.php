<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Event\ModifyEventQueryConstraintsEvent;
use DERHANSEN\SfEventMgt\Service\CategoryService;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

/**
 * The repository for Events
 */
class EventRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'startdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function injectEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Disable the use of storage records, because the StoragePage can be set
     * in the plugin
     */
    public function initializeObject()
    {
        $this->defaultQuerySettings = $this->objectManager->get(Typo3QuerySettings::class);
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
        $constraints = [];
        $query = $this->createQuery();
        $query->getQuerySettings()->setIgnoreEnableFields($eventDemand->getIgnoreEnableFields());

        $this->setStoragePageConstraint($query, $eventDemand, $constraints);
        $this->setDisplayModeConstraint($query, $eventDemand, $constraints);
        $this->setCategoryConstraint($query, $eventDemand, $constraints);
        $this->setLocationConstraint($query, $eventDemand, $constraints);
        $this->setLocationCityConstraint($query, $eventDemand, $constraints);
        $this->setLocationCountryConstraint($query, $eventDemand, $constraints);
        $this->setSpeakerConstraint($query, $eventDemand, $constraints);
        $this->setOrganisatorConstraint($query, $eventDemand, $constraints);
        $this->setStartEndDateConstraint($query, $eventDemand, $constraints);
        $this->setSearchConstraint($query, $eventDemand, $constraints);
        $this->setTopEventConstraint($query, $eventDemand, $constraints);
        $this->setYearMonthDayRestriction($query, $eventDemand, $constraints);

        $modifyEventQueryConstraintsEvent = new ModifyEventQueryConstraintsEvent(
            $constraints,
            $query,
            $eventDemand,
            $this
        );
        $this->eventDispatcher->dispatch($modifyEventQueryConstraintsEvent);
        $constraints = $modifyEventQueryConstraintsEvent->getConstraints();

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
     */
    protected function setOrderingsFromDemand($query, EventDemand $eventDemand)
    {
        $orderings = [];
        $orderFieldAllowed = GeneralUtility::trimExplode(',', $eventDemand->getOrderFieldAllowed(), true);
        if ($eventDemand->getOrderField() != '' && $eventDemand->getOrderDirection() != '' &&
            !empty($orderFieldAllowed) && in_array($eventDemand->getOrderField(), $orderFieldAllowed, true)) {
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
     */
    protected function setStoragePageConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getStoragePage() !== null && $eventDemand->getStoragePage() !== '') {
            $pidList = GeneralUtility::intExplode(',', $eventDemand->getStoragePage(), true);
            $constraints['storagePage'] = $query->in('pid', $pidList);
        }
    }

    /**
     * Sets the displayMode constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setDisplayModeConstraint($query, $eventDemand, &$constraints)
    {
        switch ($eventDemand->getDisplayMode()) {
            case 'future':
                $constraints['displayMode'] = $query->greaterThan('startdate', $eventDemand->getCurrentDateTime());
                break;
            case 'current_future':
                $constraints['displayMode'] = $query->logicalOr([
                    $query->greaterThan('startdate', $eventDemand->getCurrentDateTime()),
                    $query->logicalAnd([
                        $query->greaterThanOrEqual('enddate', $eventDemand->getCurrentDateTime()),
                        $query->lessThanOrEqual('startdate', $eventDemand->getCurrentDateTime())
                    ])
                ]);
                break;
            case 'past':
                $constraints['displayMode'] = $query->lessThanOrEqual('enddate', $eventDemand->getCurrentDateTime());
                break;
            case 'time_restriction':
                if (!empty($eventDemand->getTimeRestrictionLow())) {
                    $timeRestriction = strtotime($eventDemand->getTimeRestrictionLow());
                    $timeRestrictionConstraints['timeRestrictionLow'] = $query->greaterThanOrEqual('startdate', $timeRestriction);

                    if ($eventDemand->getIncludeCurrent()) {
                        $includeCurrentConstraint = $query->logicalAnd(
                            $query->lessThan('startdate', $timeRestriction),
                            $query->greaterThan('enddate', $timeRestriction),
                        );
                    }
                }
                if (!empty($eventDemand->getTimeRestrictionHigh())) {
                    $timeRestrictionHigh = strtotime($eventDemand->getTimeRestrictionHigh());
                    $timeRestrictionConstraints['timeRestrictionHigh'] = $query->lessThanOrEqual('startdate', $timeRestrictionHigh);
                }
                if (isset($timeRestrictionConstraints)) {
                    if ($eventDemand->getIncludeCurrent()) {
                        $constraints['displayMode'] = $query->logicalOr(
                            $includeCurrentConstraint,
                            $query->logicalAnd($timeRestrictionConstraints)
                        );
                    } else {
                        $constraints['displayMode'] = $query->logicalAnd($timeRestrictionConstraints);
                    }
                }
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
     */
    protected function setCategoryConstraint($query, $eventDemand, &$constraints)
    {
        // If no category constraint is set, categories should not be respected in the query
        if ($eventDemand->getCategoryConjunction() === '') {
            return;
        }

        if ($eventDemand->getCategory() != '') {
            $categoryConstraints = [];
            if ($eventDemand->getIncludeSubcategories()) {
                $categoryList = CategoryService::getCategoryListWithChilds($eventDemand->getCategory());
                $categories = GeneralUtility::intExplode(',', $categoryList, true);
            } else {
                $categories = GeneralUtility::intExplode(',', $eventDemand->getCategory(), true);
            }
            foreach ($categories as $category) {
                $categoryConstraints[] = $query->contains('category', $category);
            }
            if (count($categoryConstraints) > 0) {
                $constraints['category'] = $this->getCategoryConstraint($query, $eventDemand, $categoryConstraints);
            }
        }
    }

    /**
     * Returns the category constraint depending on the category conjunction configured in eventDemand
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
     * @param array $categoryConstraints
     * @return mixed
     */
    public function getCategoryConstraint($query, $eventDemand, $categoryConstraints)
    {
        switch (strtolower($eventDemand->getCategoryConjunction())) {
            case 'and':
                $constraint = $query->logicalAnd($categoryConstraints);
                break;
            case 'notor':
                $constraint = $query->logicalNot($query->logicalOr($categoryConstraints));
                break;
            case 'notand':
                $constraint = $query->logicalNot($query->logicalAnd($categoryConstraints));
                break;
            case 'or':
            default:
                $constraint = $query->logicalOr($categoryConstraints);
        }

        return $constraint;
    }

    /**
     * Sets the location constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setLocationConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocation() !== null && $eventDemand->getLocation() != '') {
            $constraints['location'] = $query->equals('location', $eventDemand->getLocation());
        }
    }

    /**
     * Sets the location.city constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setLocationCityConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocationCity() !== null && $eventDemand->getLocationCity() != '') {
            $constraints['locationCity'] = $query->equals('location.city', $eventDemand->getLocationCity());
        }
    }

    /**
     * Sets the location.country constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setLocationCountryConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getLocationCountry() !== null && $eventDemand->getLocationCountry() != '') {
            $constraints['locationCountry'] = $query->equals('location.country', $eventDemand->getLocationCountry());
        }
    }

    /**
     * Sets the speaker constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setSpeakerConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getSpeaker() !== null && $eventDemand->getSpeaker() != '') {
            $constraints['speaker'] = $query->contains('speaker', $eventDemand->getSpeaker());
        }
    }

    /**
     * Sets the organisator constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setOrganisatorConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getOrganisator() !== null && $eventDemand->getOrganisator() != '') {
            $constraints['organisator'] = $query->equals('organisator', $eventDemand->getOrganisator());
        }
    }

    /**
     * Sets the start- and enddate constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setStartEndDateConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getSearchDemand() && $eventDemand->getSearchDemand()->getStartDate() !== null &&
            $eventDemand->getSearchDemand()->getEndDate() !== null
        ) {
            /* StartDate and EndDate  - Search for events between two given dates */
            $begin = $eventDemand->getSearchDemand()->getStartDate();
            $end = $eventDemand->getSearchDemand()->getEndDate();
            $constraints['startEndDate'] = $query->logicalOr([
                $query->between('startdate', $begin, $end),
                $query->between('enddate', $begin, $end),
                $query->logicalAnd([
                    $query->greaterThanOrEqual('enddate', $begin),
                    $query->lessThanOrEqual('startdate', $begin)
                ])
            ]);
        } elseif ($eventDemand->getSearchDemand() && $eventDemand->getSearchDemand()->getStartDate() !== null) {
            /* StartDate - Search for events beginning at a given date */
            $constraints['startDate'] = $query->greaterThanOrEqual('startdate', $eventDemand->getSearchDemand()->getStartDate());
        } elseif ($eventDemand->getSearchDemand() && $eventDemand->getSearchDemand()->getEndDate() !== null) {
            /* EndDate - Search for events ending on a given date */
            $constraints['endDate'] = $query->lessThanOrEqual('enddate', $eventDemand->getSearchDemand()->getEndDate());
        }
    }

    /**
     * Sets the search constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
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
                    $searchConstraints[] = $query->like($field, '%' . addcslashes($searchSubject, '_%') . '%');
                }
            }

            if (count($searchConstraints)) {
                $constraints['search'] = $query->logicalOr($searchConstraints);
            }
        }
    }

    /**
     * Sets the topEvent constraint to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query Query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand EventDemand
     * @param array $constraints Constraints
     */
    protected function setTopEventConstraint($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getTopEventRestriction() > 0) {
            $constraints['topEvent'] = $query->equals('topEvent', (bool)($eventDemand->getTopEventRestriction() - 1));
        }
    }

    /**
     * Sets the restriction for year, year/month or year/month/day to the given constraints array
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $eventDemand
     * @param array $constraints
     */
    protected function setYearMonthDayRestriction($query, $eventDemand, &$constraints)
    {
        if ($eventDemand->getYear() > 0) {
            if ($eventDemand->getMonth() > 0) {
                if ($eventDemand->getDay() > 0) {
                    $begin = mktime(0, 0, 0, $eventDemand->getMonth(), $eventDemand->getDay(), $eventDemand->getYear());
                    $end = mktime(23, 59, 59, $eventDemand->getMonth(), $eventDemand->getDay(), $eventDemand->getYear());
                } else {
                    $begin = mktime(0, 0, 0, $eventDemand->getMonth(), 1, $eventDemand->getYear());
                    $end = mktime(23, 59, 59, ($eventDemand->getMonth() + 1), 0, $eventDemand->getYear());
                }
            } else {
                $begin = mktime(0, 0, 0, 1, 1, $eventDemand->getYear());
                $end = mktime(23, 59, 59, 12, 31, $eventDemand->getYear());
            }
            $constraints['yearMonthDay'] = $query->logicalOr([
                $query->between('startdate', $begin, $end),
                $query->between('enddate', $begin, $end),
                $query->logicalAnd([
                    $query->greaterThanOrEqual('enddate', $begin),
                    $query->lessThanOrEqual('startdate', $begin)
                ])
            ]);
        }
    }
}
