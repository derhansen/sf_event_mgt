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

    if (TYPO3_MODE === 'BE') {
        /**
         * Register Administration Module
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
            'DERHANSEN.sf_event_mgt',
            'web',
            'tx_sfeventmgt_m1',
            '',
            [
                'Administration' => 'list, export, handleExpiredRegistrations, indexNotify, notify, settingsError, 
                    newEvent, newLocation, newOrganisator, newSpeaker',
            ],
            [
                'access' => 'user,group',
                'icon' => 'EXT:sf_event_mgt/Resources/Public/Icons/events.svg',
                'labels' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_modadministration.xlf',
            ]
        );

        /**
         * Register icons
         */
        $icons = [
            'apps-pagetree-folder-contains-events' => 'events-folder.png',
            'ext-sfeventmgt-wizard' => 'events.svg',
            'ext-sfeventmgt-registration-unconfirmed' => 'tx_sfeventmgt_domain_model_registration_unconfirmed.svg',
            'ext-sfeventmgt-registration-confirmed' => 'tx_sfeventmgt_domain_model_registration_confirmed.svg',
            'ext-sfeventmgt-event' => 'tx_sfeventmgt_domain_model_event.svg',
            'ext-sfeventmgt-priceoption' => 'tx_sfeventmgt_domain_model_priceoption.svg',
            'ext-sfeventmgt-organisator' => 'tx_sfeventmgt_domain_model_organisator.svg',
            'ext-sfeventmgt-location' => 'tx_sfeventmgt_domain_model_location.svg',
            'ext-sfeventmgt-speaker' => 'tx_sfeventmgt_domain_model_speaker.svg',
            'ext-sfeventmgt-registration-field' => 'tx_sfeventmgt_domain_model_registration_field.svg',
            'ext-sfeventmgt-logfile' => 'logfile.svg',
            'ext-sfeventmgt-action-handle-expired' => 'hande-expired-registrations.svg'
        ];
        /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        foreach ($icons as $identifier => $path) {
            $iconRegistry->registerIcon(
                $identifier,
                \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
                ['source' => 'EXT:sf_event_mgt/Resources/Public/Icons/' . $path]
            );
        }
    }
});
