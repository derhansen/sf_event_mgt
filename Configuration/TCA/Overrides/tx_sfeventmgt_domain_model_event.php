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

// Enable language synchronisation for the category field
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['category']['config']['behaviour']['allowLanguageSynchronization'] = true;

// Make several fields editable for TYPO3 7.6 - translation records have problems with
// 'l10n_display' => 'defaultAsReadonly' in combination with 'l10n_mode' => 'exclude'
if (version_compare(TYPO3_branch, '7.6', '<=')) {
    $fields = ['startdate', 'enddate'];
    foreach ($fields as $field) {
        $GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns'][$field]['l10n_mode'] = 'mergeIfNotBlank';
        unset($GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns'][$field]['l10n_display']);
    }
}
