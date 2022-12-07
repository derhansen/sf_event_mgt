<?php

use DERHANSEN\SfEventMgt\Controller\AdministrationController;

return [
    'tx_sfeventmgt' => [
        'parent' => 'web',
        'position' => ['after' => 'web_info'],
        'access' => 'user',
        'workspaces' => 'live',
        'path' => '/module/page/events',
        'labels' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_modadministration.xlf',
        'extensionName' => 'SfEventMgt',
        'controllerActions' => [
            AdministrationController::class => [
                'list', 'export', 'handleExpiredRegistrations', 'indexNotify', 'notify', 'settingsError'
            ],
        ],
    ],
];
