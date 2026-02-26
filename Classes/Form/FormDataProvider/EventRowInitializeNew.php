<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class EventRowInitializeNew implements FormDataProviderInterface
{
    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_sfeventmgt_domain_model_event') {
            return $result;
        }

        if ($result['command'] === 'new') {
            $timestamp = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('date', 'timestamp');
            $startDate = (new \DateTimeImmutable())->setTimestamp($timestamp);
            $endDate = (new \DateTimeImmutable())->setTimestamp($timestamp + 3600);
            $result['databaseRow']['startdate'] = $startDate;
            $result['databaseRow']['enddate'] = $endDate;
        }

        return $result;
    }
}
