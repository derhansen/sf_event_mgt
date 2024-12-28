<?php

return [
    'ext-sfeventmgt-default' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/events.svg',
    ],
    'apps-pagetree-folder-contains-events' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/apps-pagetree-folder-contains-events.svg',
    ],
    'ext-sfeventmgt-registration-unconfirmed' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_unconfirmed.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-registration-unconfirmed',
    ],
    'ext-sfeventmgt-registration-confirmed' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_confirmed.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-registration-confirmed',
    ],
    'ext-sfeventmgt-event' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_event.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-event',
    ],
    'ext-sfeventmgt-priceoption' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_priceoption.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-priceoption',
    ],
    'ext-sfeventmgt-organisator' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_organisator.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-organisator',
    ],
    'ext-sfeventmgt-location' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_location.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-location',
    ],
    'ext-sfeventmgt-speaker' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_speaker.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-speaker',
    ],
    'ext-sfeventmgt-registration-field' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_field.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-registration-field',
    ],
    'ext-sfeventmgt-logfile' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/logfile.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-logfile',
    ],
    'ext-sfeventmgt-action-handle-expired' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgSpriteIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/hande-expired-registrations.svg',
        'sprite' => 'EXT:sf_event_mgt/Resources/Public/Icons/backend-sprites.svg#ext-sfeventmgt-action-handle-expired',
    ],
];
