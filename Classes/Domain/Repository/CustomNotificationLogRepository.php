<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

/**
 * The repository for custom notification log entries
 */
class CustomNotificationLogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'tstamp' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    ];
}
