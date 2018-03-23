<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'DERHANSEN.' . $_EXTKEY,
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
    'DERHANSEN.' . $_EXTKEY,
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
    'DERHANSEN.' . $_EXTKEY,
    'Pipayment',
    [
        'Payment' => 'redirect, success, failure, cancel, notify',
    ],
    // non-cacheable actions
    [
        'Payment' => 'redirect, success, failure, cancel, notify',
    ]
);

// DataHandler hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['DERHANSEN.' . $_EXTKEY] =
    'DERHANSEN\SfEventMgt\Hooks\DataHandlerHooks';

// Page layout hooks to show preview of plugins
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['sfeventmgt_pievent']['event'] =
    \DERHANSEN\SfEventMgt\Hooks\PageLayoutView::class . '->getEventPluginSummary';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['list_type_Info']['sfeventmgt_piuserreg']['userreg'] =
    \DERHANSEN\SfEventMgt\Hooks\PageLayoutView::class . '->getUserRegPluginSummary';

// Register cleanup command
if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'DERHANSEN\\SfEventMgt\\Command\\CleanupCommandController';
}

// Register longitude- and latitude-evaluator for TCA
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['DERHANSEN\\SfEventMgt\\Evaluation\\LongitudeEvaluator'] = 'EXT:sf_event_mgt/Classes/Evaluation/LongitudeEvaluator.php';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['DERHANSEN\\SfEventMgt\\Evaluation\\LatitudeEvaluator'] = 'EXT:sf_event_mgt/Classes/Evaluation/LatitudeEvaluator.php';

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
