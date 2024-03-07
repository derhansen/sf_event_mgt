<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Preview;

use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PiuserregPreviewRenderer extends AbstractPluginPreviewRenderer
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $data = [];
        $record = $item->getRecord();
        $flexFormData = GeneralUtility::xml2array($record['pi_flexform']);
        if (!is_array($flexFormData)) {
            $flexFormData = [];
        }

        $pluginName = $this->getPluginName($record);

        $this->setPluginPidConfig($data, $flexFormData, 'registrationPid', 'sDEF');
        $this->setStoragePage($data, $flexFormData, 'settings.userRegistration.storagePage');
        $this->setOrderSettings(
            $data,
            $flexFormData,
            'settings.userRegistration.orderField',
            'settings.userRegistration.orderDirection'
        );

        return $this->renderAsTable($data, $pluginName);
    }
}
