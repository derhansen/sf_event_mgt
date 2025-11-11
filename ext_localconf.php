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
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowDateTimeFields;
use TYPO3\CMS\Backend\Form\FormDataProvider\DatabaseRowInitializeNew;
use TYPO3\CMS\Backend\Form\FormDataProvider\InitializeProcessedTca;
use TYPO3\CMS\Backend\Form\FormDataProvider\TcaInline;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pieventlist',
    [
        EventController::class => ['list'],
    ],
    // non-cacheable actions
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pieventdetail',
    [
        EventController::class => ['detail', 'icalDownload'],
    ],
    // non-cacheable actions
    [
        EventController::class => ['icalDownload'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pieventregistration',
    [
        EventController::class => ['registration', 'saveRegistration', 'saveRegistrationResult', 'confirmRegistration', 'verifyConfirmRegistration', 'cancelRegistration', 'verifyCancelRegistration'],
    ],
    // non-cacheable actions
    [
        EventController::class => ['registration', 'saveRegistration', 'saveRegistrationResult', 'confirmRegistration', 'verifyConfirmRegistration', 'cancelRegistration', 'verifyCancelRegistration'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pieventsearch',
    [
        EventController::class => ['search'],
    ],
    // non-cacheable actions
    [
        EventController::class => ['search'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pieventcalendar',
    [
        EventController::class => ['calendar'],
    ],
    // non-cacheable actions
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Piuserreg',
    [
        UserRegistrationController::class => ['list', 'detail'],
    ],
    // non-cacheable actions
    [
        UserRegistrationController::class => ['list', 'detail'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    'SfEventMgt',
    'Pipayment',
    [
        PaymentController::class => ['redirect', 'success', 'failure', 'cancel', 'notify'],
    ],
    // non-cacheable actions
    [
        PaymentController::class => ['redirect', 'success', 'failure', 'cancel', 'notify'],
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
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

// Define template path for FluidEmail template
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][1727540189] =
    'EXT:sf_event_mgt/Resources/Private/Templates/Email';
