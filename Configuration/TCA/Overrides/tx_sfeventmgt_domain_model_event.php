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
