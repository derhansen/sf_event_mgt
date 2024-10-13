<?php

defined('TYPO3') or die();

// Enable language synchronisation for some fields
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['category']['config']['behaviour']['allowLanguageSynchronization'] = true;
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['starttime']['config']['behaviour']['allowLanguageSynchronization'] = true;
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['endtime']['config']['behaviour']['allowLanguageSynchronization'] = true;
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['fe_group']['config']['behaviour']['allowLanguageSynchronization'] = true;
