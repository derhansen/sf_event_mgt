<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * Add new select group for list_type
 */
ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'list_type',
    'sf_event_mgt',
    'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:CType.div.plugingroup',
    'after:default'
);

/**
 * Register all plugins with flexform settings
 */
$plugins = [
    'Pieventlist' => [
        'flexForm' => 'Pieventlist.xml',
    ],
    'Pieventdetail' => [
        'flexForm' => 'Pieventdetail.xml',
    ],
    'Pieventregistration' => [
        'flexForm' => 'Pieventregistration.xml',
    ],
    'Pieventsearch' => [
        'flexForm' => 'Pieventsearch.xml',
    ],
    'Pieventcalendar' => [
        'flexForm' => 'Pieventcalendar.xml',
    ],
    'Pipayment' => [
        'flexForm' => null,
    ],
    'Piuserreg' => [
        'flexForm' => 'Piuserreg.xml',
    ],
];

foreach ($plugins as $pluginName => $pluginConfig) {
    $signature = 'sfeventmgt_' . strtolower($pluginName);

    // Register plugin
    ExtensionUtility::registerPlugin(
        'SfEventMgt',
        $pluginName,
        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.' . strtolower($pluginName) . '.title',
        'ext-sfeventmgt-default',
        'sf_event_mgt'
    );

    // Remove unused fields
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$signature] = 'layout,recursive,select_key,pages';

    // Register flexform if required
    if (($pluginConfig['flexForm'] ?? null)) {
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$signature] = 'pi_flexform';
        ExtensionManagementUtility::addPiFlexFormValue(
            $signature,
            'FILE:EXT:sf_event_mgt/Configuration/FlexForms/' . $pluginConfig['flexForm']
        );
    }
}

/**
 * Register event as "Insert Record"
 */
ExtensionManagementUtility::addToInsertRecords('tx_sfeventmgt_domain_model_event');
