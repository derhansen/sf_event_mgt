<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Extbase\Service\CacheService;

/**
 * UtilityService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UtilityService
{
    /**
     * CacheService
     *
     * @var \TYPO3\CMS\Extbase\Service\CacheService
     * */
    protected $cacheService;

    /**
     * Settings Service
     *
     * @var \DERHANSEN\SfEventMgt\Service\SettingsService
     * */
    protected $settingsService;

    /**
     * DI for $cacheService
     *
     * @param CacheService $cacheService
     */
    public function injectCacheService(\TYPO3\CMS\Extbase\Service\CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * DI for $settingsService
     *
     * @param SettingsService $settingsService
     */
    public function injectSettingsService(\DERHANSEN\SfEventMgt\Service\SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Clears the cache of configured pages in TypoScript
     *
     * @param array $settings The settings
     *
     * @return void
     */
    public function clearCacheForConfiguredUids($settings)
    {
        $pidList = $this->settingsService->getClearCacheUids($settings);
        if (count($pidList) > 0) {
            $this->cacheService->clearPageCache($pidList);
        }
    }
}
