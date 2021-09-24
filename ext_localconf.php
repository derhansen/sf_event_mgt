<?php

defined('TYPO3_MODE') or die();

call_user_func(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pievent',
        [
            \DERHANSEN\SfEventMgt\Controller\EventController::class => 'list, detail, calendar, registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration, icalDownload, search',
        ],
        // non-cacheable actions
        [
            \DERHANSEN\SfEventMgt\Controller\EventController::class => 'registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration, icalDownload, search',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Piuserreg',
        [
            \DERHANSEN\SfEventMgt\Controller\UserRegistrationController::class => 'list',
        ],
        // non-cacheable actions
        [
            \DERHANSEN\SfEventMgt\Controller\UserRegistrationController::class => 'list',
        ]
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pipayment',
        [
            \DERHANSEN\SfEventMgt\Controller\PaymentController::class => 'redirect, success, failure, cancel, notify',
        ],
        // non-cacheable actions
        [
            \DERHANSEN\SfEventMgt\Controller\PaymentController::class => 'redirect, success, failure, cancel, notify',
        ]
    );

    // DataHandler hooks
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['DERHANSEN.sf_event_mgt'] =
        'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['DERHANSEN.sf_event_mgt'] =
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
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        \DERHANSEN\SfEventMgt\Evaluation\TimeRestrictionEvaluator::class
    ] = '';

    // Implement get_cache_timeout hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['get_cache_timeout'][] =
        \DERHANSEN\SfEventMgt\Hooks\PageCache::class . '->getCacheTimeout';

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
        <INCLUDE_TYPOSCRIPT: source="FILE:EXT:sf_event_mgt/Configuration/TSConfig/Mod/Wizards/ContentElement.tsconfig">
    ');

    // Custom FormDataProvider to hide TCA inline fields for registrations on given conditions
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
    \DERHANSEN\SfEventMgt\Form\FormDataProvider\HideInlineRegistrations::class
    ] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\InitializeProcessedTca::class,
        ],
        'before' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\TcaInline::class,
        ]
    ];

    // Custom FormDataProvider for event plausability checks
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
    \DERHANSEN\SfEventMgt\Form\FormDataProvider\EventPlausability::class
    ] = [
        'depends' => [
            \TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDateTimeFields::class,
        ]
    ];

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('linkvalidator')) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:sf_event_mgt/Configuration/TSConfig/Mod/Page/mod.linkvalidator.txt">'
        );
    }
});
