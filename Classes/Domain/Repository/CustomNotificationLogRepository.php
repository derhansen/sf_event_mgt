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
    protected $defaultOrderings = array(
        'tstamp' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
    );

}