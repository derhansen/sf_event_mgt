<?php

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$slugBehaviour = GeneralUtility::makeInstance(ExtensionConfiguration::class)
    ->get('sf_event_mgt', 'slugBehaviour');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_speaker',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'origUid' => 't3_origuid',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,job_title,description',
        'typeicon_classes' => [
            'default' => 'ext-sfeventmgt-speaker',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'name, job_title, slug, description,
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:event.tabs.media,
                    image,
                --div--;LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:tabs.language,
                    --palette--;;language,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    hidden,--palette--;;timeRestriction',
        ],
    ],
    'palettes' => [
        'timeRestriction' => ['showitem' => 'starttime, endtime'],
        'language' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource'],
    ],
    'columns' => [
        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_speaker.name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'required' => true,
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
            'displayCond' => 'VERSION:IS:false',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['name'],
                    'replacements' => [
                        '/' => '-',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => $slugBehaviour,
                'default' => '',
            ],
        ],
        'job_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_speaker.job_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_speaker.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf:tx_sfeventmgt_domain_model_speaker.image',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
    ],
];
