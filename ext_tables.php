<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_event',
    'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_event.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_event');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_location',
    'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_location.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_location');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_organisator',
    'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_organisator.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_organisator');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_registration',
    'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_registration.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_registration');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_sfeventmgt_domain_model_priceoption',
    'EXT:sf_event_mgt/Resources/Private/Language/locallang_csh_tx_sfeventmgt_domain_model_priceoption.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_sfeventmgt_domain_model_priceoption');

if (TYPO3_MODE === 'BE') {
    // Register Administration Module
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'DERHANSEN.sf_event_mgt',
        'web',
        'tx_sfeventmgt_m1',
        '',
        [
            'Administration' => 'list, export, handleExpiredRegistrations, indexNotify, notify, settingsError',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:' . $_EXTKEY . '/Resources/Public/Icons/events.svg',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_modadministration.xlf',
        ]
    );


    $icons = [
        'apps-pagetree-folder-contains-events' => 'events-folder.png',
        'ext-sfeventmgt-registration-unconfirmed' => 'tx_sfeventmgt_domain_model_registration_unconfirmed.gif',
        'ext-sfeventmgt-registration-confirmed' => 'tx_sfeventmgt_domain_model_registration_confirmed.gif',
    ];
    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    foreach ($icons as $identifier => $path) {
        $iconRegistry->registerIcon(
            $identifier,
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:sf_event_mgt/Resources/Public/Icons/' . $path]
        );
    }
}
