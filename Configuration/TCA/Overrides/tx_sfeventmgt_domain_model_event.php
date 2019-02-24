<?php
defined('TYPO3_MODE') or die();

$tableName = 'tx_sfeventmgt_domain_model_event';

// Add an extra categories selection field to the events table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'sf_event_mgt',
    $tableName,
    'category',
    [
        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:tx_sfeventmgt_domain_model_event.category',
        'exclude' => false
    ]
);

// Enable language synchronisation for the category field
$GLOBALS['TCA'][$tableName]['columns']['category']['config']['behaviour']['allowLanguageSynchronization'] = true;

// Register slug field for TYPO3 9.5
if (\DERHANSEN\SfEventMgt\Utility\MiscUtility::isV9Lts()) {
    $eventColumns['slug'] = [
        'exclude' => true,
        'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
        'config' => [
            'type' => 'slug',
            'size' => 50,
            'generatorOptions' => [
                'fields' => ['title'],
                'replacements' => [
                    '/' => '-'
                ],
            ],
            'fallbackCharacter' => '-',
            'eval' => 'uniqueInSite',
            'default' => ''
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        $tableName,
        $eventColumns
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        $tableName,
        'slug',
        '',
        'after:title'
    );
}
