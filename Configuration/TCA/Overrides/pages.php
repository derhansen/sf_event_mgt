<?php

defined('TYPO3') or die();

// Override events icon
$GLOBALS['TCA']['pages']['columns']['module']['config']['items'][] = [
    0 => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:events-folder',
    1 => 'events',
    2 => 'apps-pagetree-folder-contains-events',
];

$GLOBALS['TCA']['pages']['ctrl']['typeicon_classes']['contains-events'] = 'apps-pagetree-folder-contains-events';
