<?php

defined('TYPO3') or die();

call_user_func(function () {
    $tables = [
        'tx_sfeventmgt_domain_model_event',
        'tx_sfeventmgt_domain_model_location',
        'tx_sfeventmgt_domain_model_organisator',
        'tx_sfeventmgt_domain_model_registration',
        'tx_sfeventmgt_domain_model_priceoption',
        'tx_sfeventmgt_domain_model_speaker',
        'tx_sfeventmgt_domain_model_registration_field',
        'tx_sfeventmgt_domain_model_registration_fieldvalue',
    ];

    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
    }
});
