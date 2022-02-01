<?php

defined('TYPO3') or die();

// Enable language synchronisation for the category field
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['category']['config']['behaviour']['allowLanguageSynchronization'] = true;

$extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('sf_event_mgt');

if (empty($extensionConfiguration['contentElementRelation'])) {
    unset($GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['content_elements']);
}
