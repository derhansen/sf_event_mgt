<?php

return [
    'apps-pagetree-folder-contains-events' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/apps-pagetree-folder-contains-events.svg'
    ],
    'ext-sfeventmgt-wizard' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/events.svg'
    ],
    'ext-sfeventmgt-registration-unconfirmed' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_unconfirmed.svg'
    ],
    'ext-sfeventmgt-registration-confirmed' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_confirmed.svg'
    ],
    'ext-sfeventmgt-event' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_event.svg'
    ],
    'ext-sfeventmgt-priceoption' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_priceoption.svg'
    ],
    'ext-sfeventmgt-organisator' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_organisator.svg'
    ],
    'ext-sfeventmgt-location' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_location.svg'
    ],
    'ext-sfeventmgt-speaker' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_speaker.svg'
    ],
    'ext-sfeventmgt-registration-field' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/tx_sfeventmgt_domain_model_registration_field.svg'
    ],
    'ext-sfeventmgt-logfile' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/logfile.svg'
    ],
    'ext-sfeventmgt-action-handle-expired' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:sf_event_mgt/Resources/Public/Icons/hande-expired-registrations.svg'
    ],
];



$icons = [
    'apps-pagetree-folder-contains-events' => 'apps-pagetree-folder-contains-events.svg',
    'ext-sfeventmgt-wizard' => 'events.svg',
    'ext-sfeventmgt-registration-unconfirmed' => 'tx_sfeventmgt_domain_model_registration_unconfirmed.svg',
    'ext-sfeventmgt-registration-confirmed' => 'tx_sfeventmgt_domain_model_registration_confirmed.svg',
    'ext-sfeventmgt-event' => 'tx_sfeventmgt_domain_model_event.svg',
    'ext-sfeventmgt-priceoption' => 'tx_sfeventmgt_domain_model_priceoption.svg',
    'ext-sfeventmgt-organisator' => 'tx_sfeventmgt_domain_model_organisator.svg',
    'ext-sfeventmgt-location' => 'tx_sfeventmgt_domain_model_location.svg',
    'ext-sfeventmgt-speaker' => 'tx_sfeventmgt_domain_model_speaker.svg',
    'ext-sfeventmgt-registration-field' => 'tx_sfeventmgt_domain_model_registration_field.svg',
    'ext-sfeventmgt-logfile' => 'logfile.svg',
    'ext-sfeventmgt-action-handle-expired' => 'hande-expired-registrations.svg'
];

foreach ($icons as $identifier => $path) {
    $iconRegistry->registerIcon(
        $identifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
        ['source' => 'EXT:sf_event_mgt/Resources/Public/Icons/' . $path]
    );
}
