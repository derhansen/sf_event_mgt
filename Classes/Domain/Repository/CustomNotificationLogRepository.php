<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for custom notification log entries
 */
class CustomNotificationLogRepository extends Repository
{
    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'tstamp' => QueryInterface::ORDER_DESCENDING
    ];
}
