<?php

defined('TYPO3_MODE') or die();

call_user_func(function () {
    /**
     * Table description files for localization and allowing sf_event_mgt tables on pages of type default
     */
    $tables = [
        'tx_sfeventmgt_domain_model_event',
        'tx_sfeventmgt_domain_model_location',
        'tx_sfeventmgt_domain_model_organisator',
        'tx_sfeventmgt_domain_model_registration',
        'tx_sfeventmgt_domain_model_priceoption',
        'tx_sfeventmgt_domain_model_speaker',
        'tx_sfeventmgt_domain_model_registration_field',
        'tx_sfeventmgt_domain_model_registration_fieldvalue'
    ];

    foreach ($tables as $table) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
            $table,
            'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_' . $table . '.xlf'
        );
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
    }

    /**
     * Register Administration Module
     */
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'SfEventMgt',
        'web',
        'tx_sfeventmgt_m1',
        '',
        [
            \DERHANSEN\SfEventMgt\Controller\AdministrationController::class => 'list, export, handleExpiredRegistrations, indexNotify, notify, settingsError',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:sf_event_mgt/Resources/Public/Icons/module.svg',
            'labels' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_modadministration.xlf',
        ]
    );
});
