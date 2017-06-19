<?php
defined('TYPO3_MODE') or die();

// Add an extra categories selection field to the events table
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    'sf_event_mgt',
    'tx_sfeventmgt_domain_model_event',
    'category',
    [
        'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf:tx_sfeventmgt_domain_model_event.category',
        'exclude' => false
    ]
);
