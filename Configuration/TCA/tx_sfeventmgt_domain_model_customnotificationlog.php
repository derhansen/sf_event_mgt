<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog',
        'label' => 'details',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'searchFields' => 'title,',
        'hideTable' => true,
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-logfile',
        ],
    ],
    'types' => [
        '1' => ['showitem' => ''],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'cruser_id' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'event' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.event',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_sfeventmgt_domain_model_event',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'details' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.details',
            'config' => [
                'type' => 'input',
            ],
        ],
        'message' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.message',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 5,
            ],
        ],
        'emails_sent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.emailsSent',
            'config' => [
                'type' => 'input',
            ],
        ],
        'tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.tstamp',
            'config' => [
                'type' => 'input',
            ],
        ],
    ],
];
