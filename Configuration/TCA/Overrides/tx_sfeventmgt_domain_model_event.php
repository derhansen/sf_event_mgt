<?php

defined('TYPO3') or die();

// Enable language synchronisation for the category field
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_event']['columns']['category']['config']['behaviour']['allowLanguageSynchronization'] = true;
