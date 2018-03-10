<?php
namespace DERHANSEN\SfEventMgt\Domain\Repository;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * The repository for custom notification log entries
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CustomNotificationLogRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Set default sorting
     *
     * @var array
     */
    protected $defaultOrderings = [
        'tstamp' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    ];
}
