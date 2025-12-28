<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * Add new select group for content element
 */
ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'CType',
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
    $contentTypeName = 'sfeventmgt_' . strtolower($pluginName);

    // Register plugin
    ExtensionUtility::registerPlugin(
        'SfEventMgt',
        $pluginName,
        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.' . strtolower($pluginName) . '.title',
        'ext-sfeventmgt-default',
        'sf_event_mgt'
    );

    // Register flexform if required
    $flexFormTab = '';
    if ($pluginConfig['flexForm'] ?? null) {
        ExtensionManagementUtility::addPiFlexFormValue(
            '*',
            'FILE:EXT:sf_event_mgt/Configuration/FlexForms/' . $pluginConfig['flexForm'],
            $contentTypeName
        );
        $flexFormTab = '--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,pi_flexform,';
    }

    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        ' . $flexFormTab . '
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';
}

/**
 * Register event as "Insert Record"
 */
ExtensionManagementUtility::addToInsertRecords('tx_sfeventmgt_domain_model_event');
