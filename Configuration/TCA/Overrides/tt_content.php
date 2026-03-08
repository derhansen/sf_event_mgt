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

    $flexForm = '';
    if ($pluginConfig['flexForm'] ?? null) {
        $flexForm = 'FILE:EXT:sf_event_mgt/Configuration/FlexForms/' . $pluginConfig['flexForm'];
    }

    // Register plugin
    ExtensionUtility::registerPlugin(
        'SfEventMgt',
        $pluginName,
        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.' . strtolower($pluginName) . '.title',
        'ext-sfeventmgt-plugin',
        'sf_event_mgt',
        'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:plugin.' . strtolower($pluginName) . '.description',
        $flexForm
    );
}

/**
 * Register event as "Insert Record"
 */
ExtensionManagementUtility::addToInsertRecords('tx_sfeventmgt_domain_model_event');
