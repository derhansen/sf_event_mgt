<?php
defined('TYPO3_MODE') or die();

$additionalSysFileReferenceColumns = [
    'show_in_views' => [
        'exclude' => true,
        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.0', 0, ''],
                ['LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.1', 1, ''],
                ['LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.2', 2, ''],
            ],
            'default' => 0,
        ]
    ],
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'sys_file_reference',
    $additionalSysFileReferenceColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'sys_file_reference',
    'eventPalette',
    'show_in_views'
);
