<?php
namespace DERHANSEN\SfEventMgt\Hooks;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook for Template Layouts
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class TemplateLayouts
{
    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     *
     * @param array $config Configuration array
     *
     * @return void
     */
    public function user_templateLayout(array &$config)
    {
        $templateLayouts = $this->getTemplateLayoutsFromTsConfig($config['flexParentDatabaseRow']['pid']);
        foreach ($templateLayouts as $index => $layout) {
            $additionalLayout = [
                $GLOBALS['LANG']->sL($layout, true),
                $index
            ];
            array_push($config['items'], $additionalLayout);
        }
    }

    /**
     * Get template layouts defined in TsConfig
     *
     * @param int $pageUid PageUID
     *
     * @return array
     */
    protected function getTemplateLayoutsFromTsConfig($pageUid)
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
}
