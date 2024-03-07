<?php

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$additionalSysFileReferenceColumns = [
    'show_in_views' => [
        'exclude' => true,
        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                [
                    'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.0',
                    'value' => 0,
                ],
                [
                    'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.1',
                    'value' => 1,
                ],
                [
                    'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:sys_file_reference.show_in_views.2',
                    'value' => 2,
                ],
            ],
            'default' => 0,
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns(
    'sys_file_reference',
    $additionalSysFileReferenceColumns
);
ExtensionManagementUtility::addFieldsToPalette(
    'sys_file_reference',
    'eventPalette',
    'show_in_views'
);
