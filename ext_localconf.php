<?php
defined('TYPO3_MODE') or die();

call_user_func(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'DERHANSEN.sf_event_mgt',
        'Pievent',
        [
            'Event' => 'list, detail, calendar, registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration, icalDownload, search',
        ],
        // non-cacheable actions
        [
            'Event' => 'registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration, icalDownload, search',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'DERHANSEN.sf_event_mgt',
        'Piuserreg',
        [
            'UserRegistration' => 'list',
        ],
        // non-cacheable actions
        [
            'UserRegistration' => 'list',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'DERHANSEN.sf_event_mgt',
        'Pipayment',
        [
            'Payment' => 'redirect, success, failure, cancel, notify',
        ],
        // non-cacheable actions
        [
            'Payment' => 'redirect, success, failure, cancel, notify',
        ]
    );

    // DataHandler hooks
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['DERHANSEN.sf_event_mgt'] =
        'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['event_clearcache'] =
        \DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks::class . '->clearCachePostProc';

    // Page layout hooks to show preview of plugins
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['sfeventmgt_pievent']['event'] =
        \DERHANSEN\SfEventMgt\Hooks\PageLayoutView::class . '->getEventPluginSummary';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['sfeventmgt_piuserreg']['userreg'] =
        \DERHANSEN\SfEventMgt\Hooks\PageLayoutView::class . '->getUserRegPluginSummary';

    if (TYPO3_MODE === 'BE') {
        // Enable live search for events using "#event:"
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['event'] = 'tx_sfeventmgt_domain_model_event';
    }

    // Register longitude- and latitude-evaluator for TCA
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        \DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator::class
    ] = '';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        \DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator::class
    ] = '';

    // Register default payment methods
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] = [
        'invoice' => [
            'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Invoice',
            'extkey' => 'sf_event_mgt'
        ],
        'transfer' => [
            'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Transfer',
            'extkey' => 'sf_event_mgt'
        ]
    ];

    // Add page TSConfig
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
        <INCLUDE_TYPOSCRIPT: source="FILE:EXT:sf_event_mgt/Configuration/TSConfig/Mod/Wizards/ContentElement.txt">
    ');
});
