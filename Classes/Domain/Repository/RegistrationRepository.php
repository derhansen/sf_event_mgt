<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for registrations
 */
class RegistrationRepository extends Repository
{
    /**
     * Disable the use of storage records, because the StoragePage can be set
     * in the plugin
     */
    public function initializeObject(): void
    {
        $this->defaultQuerySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $this->defaultQuerySettings->setRespectStoragePage(false);
    }

    /**
     * Returns all registrations for the given event with the given constraints
     * Constraints are combined with a logical AND
     *
     * @param Event $event Event
     * @param CustomNotification $customNotification
     * @param array $findConstraints FindConstraints
     *
     * @return array|QueryResultInterface
     */
    public function findNotificationRegistrations(
        Event $event,
        CustomNotification $customNotification,
        array $findConstraints = []
    ) {
        $constraints = [];
        $query = $this->createQuery();
        $constraints[] = $query->equals('event', $event);
        $constraints[] = $query->equals('ignoreNotifications', false);

        if ($customNotification->getRecipients() === CustomNotification::RECIPIENTS_CONFIRMED) {
            $constraints[] = $query->equals('confirmed', true);
        } elseif ($customNotification->getRecipients() === CustomNotification::RECIPIENTS_UNCONFIRMED) {
            $constraints[] = $query->equals('confirmed', false);
        }

        if (!is_array($findConstraints) || count($findConstraints) == 0) {
            return $query->matching($query->logicalAnd($constraints))->execute();
        }

        foreach ($findConstraints as $findConstraint => $value) {
            $condition = key($value);
            switch ($condition) {
                case 'equals':
                    $constraints[] = $query->equals($findConstraint, $value[$condition]);
                    break;
                case 'lessThan':
                    $constraints[] = $query->lessThan($findConstraint, $value[$condition]);
                    break;
                case 'lessThanOrEqual':
                    $constraints[] = $query->lessThanOrEqual($findConstraint, $value[$condition]);
                    break;
                case 'greaterThan':
                    $constraints[] = $query->greaterThan($findConstraint, $value[$condition]);
                    break;
                case 'greaterThanOrEqual':
                    $constraints[] = $query->greaterThanOrEqual($findConstraint, $value[$condition]);
                    break;
                default:
                    throw new \InvalidArgumentException('An error occured - Unknown condition: ' . $condition);
            }
        }

        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Returns registrations for the given UserRegistrationDemand demand
     *
     * @param UserRegistrationDemand $demand
     * @return array|QueryResultInterface
     */
    public function findRegistrationsByUserRegistrationDemand(UserRegistrationDemand $demand)
    {
        if (!$demand->getUser()) {
            return [];
        }
        $constraints = [];
        $query = $this->createQuery();
        $this->setStoragePageConstraint($query, $demand, $constraints);
        $this->setDisplayModeConstraint($query, $demand, $constraints);
        $this->setUserConstraint($query, $demand, $constraints);
        $this->setOrderingsFromDemand($query, $demand);

        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Returns all registrations for the given event and where the waitlist flag is as given
     *
     * @param Event $event
     * @param bool $waitlist
     * @return array|QueryResultInterface
     */
    public function findByEventAndWaitlist(Event $event, bool $waitlist = false)
    {
        $constraints = [];
        $query = $this->createQuery();
        $constraints[] = $query->equals('event', $event->getUid());
        $constraints[] = $query->equals('waitlist', $waitlist);

        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Returns all potential move up registrations for the given event ordered by "registration_date"
     *
     * @param Event $event
     * @return array|QueryResultInterface
     */
    public function findWaitlistMoveUpRegistrations(Event $event)
    {
        $constraints = [];
        $query = $this->createQuery();
        $constraints[] = $query->equals('event', $event->getUid());
        $constraints[] = $query->equals('waitlist', true);
        $constraints[] = $query->equals('confirmed', true);
        $constraints[] = $query->greaterThan('registrationDate', 0);
        $query->setOrderings(['registration_date' => QueryInterface::ORDER_ASCENDING]);

        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Sets the displayMode constraint to the given constraints array
     *
     * @param QueryInterface $query Query
     * @param UserRegistrationDemand $demand
     * @param array $constraints Constraints
     */
    protected function setDisplayModeConstraint(
        QueryInterface $query,
        UserRegistrationDemand $demand,
        array &$constraints
    ): void {
        switch ($demand->getDisplayMode()) {
            case 'future':
                $constraints[] = $query->greaterThan('event.startdate', $demand->getCurrentDateTime());
                break;
            case 'current_future':
                $constraints[] = $query->logicalOr([
                    $query->greaterThan('event.startdate', $demand->getCurrentDateTime()),
                    $query->logicalAnd([
                        $query->greaterThanOrEqual('event.enddate', $demand->getCurrentDateTime()),
                        $query->lessThanOrEqual('event.startdate', $demand->getCurrentDateTime()),
                    ]),
                ]);
                break;
            case 'past':
                $constraints[] = $query->lessThanOrEqual('event.enddate', $demand->getCurrentDateTime());
                break;
        }
    }

    /**
     * Sets the storagePage constraint to the given constraints array
     *
     * @param QueryInterface $query Query
     * @param UserRegistrationDemand $demand
     * @param array $constraints Constraints
     */
    protected function setStoragePageConstraint(
        QueryInterface $query,
        UserRegistrationDemand $demand,
        array &$constraints
    ): void {
        if ($demand->getStoragePage() !== '') {
            $pidList = GeneralUtility::intExplode(',', $demand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }
    }

    /**
     * Sets the user constraint to the given constraints array
     *
     * @param QueryInterface $query Query
     * @param UserRegistrationDemand $demand
     * @param array $constraints Constraints
     */
    protected function setUserConstraint(
        QueryInterface $query,
        UserRegistrationDemand $demand,
        array &$constraints
    ): void {
        if ($demand->getUser()) {
            $constraints[] = $query->equals('feUser', $demand->getUser());
        }
    }

    /**
     * Sets the ordering to the given query for the given demand
     *
     * @param QueryInterface $query Query
     * @param UserRegistrationDemand $demand
     */
    protected function setOrderingsFromDemand(QueryInterface $query, UserRegistrationDemand $demand): void
    {
        $orderings = [];
        if ($demand->getOrderField() !== '' && $demand->getOrderDirection() !== '') {
            $orderings[$demand->getOrderField()] = ((strtolower($demand->getOrderDirection()) === 'desc') ?
                QueryInterface::ORDER_DESCENDING :
                QueryInterface::ORDER_ASCENDING);
            $query->setOrderings($orderings);
        }
    }
}
