<?php

defined('TYPO3') or die();

// Enable language synchronisation for some fields
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_organisator']['columns']['starttime']['config']['behaviour']['allowLanguageSynchronization'] = true;
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_organisator']['columns']['endtime']['config']['behaviour']['allowLanguageSynchronization'] = true;
