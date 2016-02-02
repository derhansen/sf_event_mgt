<?php
namespace DERHANSEN\SfEventMgt\Hooks;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @todo: Remove condition when TYPO3 6.2 is deprecated
     *
     * @param array $config Configuration array
     *
     * @return void
     */
    public function user_templateLayout(array &$config)
    {
        if (GeneralUtility::compat_version('7.6')) {
            $templateLayouts = $this->getTemplateLayoutsFromTsConfig($config['flexParentDatabaseRow']['pid']);
        } else {
            $templateLayouts = $this->getTemplateLayoutsFromTsConfig($config['row']['pid']);
        }
        foreach ($templateLayouts as $index => $layout) {
            $additionalLayout = array(
                $GLOBALS['LANG']->sL($layout, true),
                $index
            );
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
        $templateLayouts = array();
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.']) &&
            is_array($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'])
        ) {
            $templateLayouts = $pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'];
        }
        return $templateLayouts;
    }

}