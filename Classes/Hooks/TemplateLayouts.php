<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;

/**
 * Hook for Template Layouts
 */
class TemplateLayouts
{
    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     */
    public function user_templateLayout(array &$config): void
    {
        $pid = (int)($config['flexParentDatabaseRow']['pid'] ?? 0);
        $templateLayouts = $this->getTemplateLayoutsFromTsConfig($pid);
        foreach ($templateLayouts as $index => $layout) {
            $additionalLayout = [
                $this->getLanguageService()->sL($layout),
                $index,
            ];
            $config['items'][] = $additionalLayout;
        }
    }

    /**
     * Get template layouts defined in TsConfig
     */
    protected function getTemplateLayoutsFromTsConfig(int $pageUid): array
    {
        $templateLayouts = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.']) &&
            is_array($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'])
        ) {
            $templateLayouts = $pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'];
        }

        return $templateLayouts;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
