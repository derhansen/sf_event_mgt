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

class EventRowInitializeNew implements FormDataProviderInterface
{
    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_sfeventmgt_domain_model_event') {
            return $result;
        }

        if ($result['command'] === 'new') {
            $result['databaseRow']['startdate'] = $GLOBALS['EXEC_TIME'];
            $result['databaseRow']['enddate'] = $GLOBALS['EXEC_TIME'] + 3600;
        }

        return $result;
    }
}
