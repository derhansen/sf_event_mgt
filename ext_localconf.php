<?php

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Controller\UserRegistrationController;
use DERHANSEN\SfEventMgt\Evaluation\LatitudeEvaluator;
use DERHANSEN\SfEventMgt\Evaluation\LongitudeEvaluator;
use DERHANSEN\SfEventMgt\Evaluation\TimeRestrictionEvaluator;
use DERHANSEN\SfEventMgt\Form\FormDataProvider\EventPlausability;
use DERHANSEN\SfEventMgt\Form\FormDataProvider\EventRowInitializeNew;
use DERHANSEN\SfEventMgt\Form\FormDataProvider\HideInlineRegistrations;
use DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks;
use DERHANSEN\SfEventMgt\Hooks\PageCache;
use DERHANSEN\SfEventMgt\Updates\PiEventPluginUpdater;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDateTimeFields;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
use TYPO3\CMS\Backend\Form\FormDataProvider\InitializeProcessedTca;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaInline;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

call_user_func(function () {
    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pieventlist',
        [
            EventController::class => 'list',
        ],
        // non-cacheable actions
        [
            EventController::class => '',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pieventdetail',
        [
            EventController::class => 'detail, icalDownload',
        ],
        // non-cacheable actions
        [
            EventController::class => 'icalDownload',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pieventregistration',
        [
            EventController::class => 'registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration',
        ],
        // non-cacheable actions
        [
            EventController::class => 'registration, saveRegistration, saveRegistrationResult, confirmRegistration, cancelRegistration',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pieventsearch',
        [
            EventController::class => 'search',
        ],
        // non-cacheable actions
        [
            EventController::class => 'search',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pieventcalendar',
        [
            EventController::class => 'calendar',
        ],
        // non-cacheable actions
        [
            EventController::class => '',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Piuserreg',
        [
            UserRegistrationController::class => 'list, detail',
        ],
        // non-cacheable actions
        [
            UserRegistrationController::class => 'list, detail',
        ]
    );

    ExtensionUtility::configurePlugin(
        'SfEventMgt',
        'Pipayment',
        [
            PaymentController::class => 'redirect, success, failure, cancel, notify',
        ],
        // non-cacheable actions
        [
            PaymentController::class => 'redirect, success, failure, cancel, notify',
        ]
    );

    // DataHandler hooks
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['DERHANSEN.sf_event_mgt'] =
        'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['DERHANSEN.sf_event_mgt'] =
        'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['event_clearcache'] =
        DataHandlerHooks::class . '->clearCachePostProc';

    // Enable live search for events using "#event:"
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['livesearch']['event'] = 'tx_sfeventmgt_domain_model_event';

    // Register longitude- and latitude-evaluator for TCA
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        LongitudeEvaluator::class
    ] = '';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        LatitudeEvaluator::class
    ] = '';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][
        TimeRestrictionEvaluator::class
    ] = '';

    // Implement get_cache_timeout hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['get_cache_timeout'][] =
        PageCache::class . '->getCacheTimeout';

    // Register default payment methods
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] = [
        'invoice' => [
            'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Invoice',
            'extkey' => 'sf_event_mgt',
        ],
        'transfer' => [
            'class' => 'DERHANSEN\\SfEventMgt\\Payment\\Transfer',
            'extkey' => 'sf_event_mgt',
        ],
    ];

    // Add page TSConfig
    ExtensionManagementUtility::addPageTSConfig('
        <INCLUDE_TYPOSCRIPT: source="FILE:EXT:sf_event_mgt/Configuration/TSConfig/Mod/Wizards/ContentElement.tsconfig">
    ');

    // Custom FormDataProvider to hide TCA inline fields for registrations on given conditions
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
    HideInlineRegistrations::class
    ] = [
        'depends' => [
            InitializeProcessedTca::class,
        ],
        'before' => [
            TcaInline::class,
        ],
    ];

    // Custom FormDataProvider for event plausability checks
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
    EventPlausability::class
    ] = [
        'depends' => [
            DatabaseRowDateTimeFields::class,
        ],
    ];

    // Custom FormDataProvider for default value of datetime fields
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord'][
            EventRowInitializeNew::class
    ] = [
        'depends' => [
            DatabaseRowInitializeNew::class,
        ],
    ];

    // Register tables for garbage collection task
    foreach (['tx_sfeventmgt_domain_model_registration', 'tx_sfeventmgt_domain_model_registration_fieldvalue'] as $table) {
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables'][$table] = [
            'dateField' => 'tstamp',
            'expirePeriod' => 30,
        ];
    }

    // Register event management plugin updater
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/install']['update']['piEventPluginUpdater']
        = PiEventPluginUpdater::class;

    if (ExtensionManagementUtility::isLoaded('linkvalidator')) {
        ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:sf_event_mgt/Configuration/TSConfig/Mod/Page/mod.linkvalidator.txt">'
        );
    }
});
