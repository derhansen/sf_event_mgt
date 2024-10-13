<?php

defined('TYPO3') or die();

// Enable language synchronisation for some fields
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_speaker']['columns']['starttime']['config']['behaviour']['allowLanguageSynchronization'] = true;
$GLOBALS['TCA']['tx_sfeventmgt_domain_model_speaker']['columns']['endtime']['config']['behaviour']['allowLanguageSynchronization'] = true;
