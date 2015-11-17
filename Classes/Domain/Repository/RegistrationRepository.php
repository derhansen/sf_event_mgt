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

/**
 * The repository for registrations
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

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
        $this->defaultQuerySettings->setRespectSysLanguage(false);
    }

    /**
     * Returns all registrations, where the confirmation date is less than the
     * given date
     *
     * @param \Datetime $dateNow Date
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findExpiredRegistrations($dateNow)
    {
        $constraints = array();
        $query = $this->createQuery();
        $constraints[] = $query->lessThanOrEqual('confirmationUntil', $dateNow);
        $constraints[] = $query->equals('confirmed', false);
        return $query->matching($query->logicalAnd($constraints))->execute();
    }

    /**
     * Returns all registrations for the given event with the given constraints
     * Constraints are combined with a logical AND
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     * @param array $findConstraints FindConstraints
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findNotificationRegistrations($event, $findConstraints)
    {
        $constraints = array();
        $query = $this->createQuery();
        $constraints[] = $query->equals('event', $event);
        $constraints[] = $query->equals('ignoreNotifications', false);

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

}