<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Form\FormDataProvider;

use DERHANSEN\SfEventMgt\Service\EventPlausabilityService;
use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Uses EventPlausabilityService to check if event contains unplausible settings.
 */
class EventPlausability implements FormDataProviderInterface
{
    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_sfeventmgt_domain_model_event') {
            return $result;
        }

        $eventPlausabilityService = GeneralUtility::makeInstance(EventPlausabilityService::class);
        $eventPlausabilityService->verifyEventStartAndEnddate(
            (int)$result['databaseRow']['startdate'],
            (int)$result['databaseRow']['enddate']
        );

        return $result;
    }
}
