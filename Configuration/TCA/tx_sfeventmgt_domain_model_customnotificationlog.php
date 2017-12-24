<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog',
        'label' => 'details',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'searchFields' => 'title,',
        'hideTable' => true,
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-logfile'
        ],
    ],
    'interface' => [
        'showRecordFieldList' => '',
    ],
    'types' => [
        '1' => ['showitem' => ''],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [

        'cruser_id' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.cruser',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'be_users',
                'foreign_class' => '\TYPO3\CMS\Beuser\Domain\Model\BackendUser',
                'maxitems' => 1
            ]
        ],
        'event' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.event',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_sfeventmgt_domain_model_event',
                'size' => 1,
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
        'details' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.details',
            'config' => [
                'type' => 'input',
            ],
        ],
        'emails_sent' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.emailsSent',
            'config' => [
                'type' => 'input',
            ],
        ],
        'tstamp' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_customnotificationlog.emailsSent',
            'config' => [
                'type' => 'input',
            ],
        ],

    ],
];
