<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * UtilityService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UtilityService {

	/**
	 * CacheService
	 *
	 * @var \TYPO3\CMS\Extbase\Service\CacheService
	 */
	protected $cacheService;

	/**
	 * Settings Service
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\SettingsService
	 */
	protected $settingsService = NULL;

	/**
	 * Injects the cache Service
	 *
	 * @param \TYPO3\CMS\Extbase\Service\CacheService $cacheService The cache service
	 *
	 * @return void
	 */
	public function injectCacheService(\TYPO3\CMS\Extbase\Service\CacheService $cacheService) {
		$this->cacheService = $cacheService;
	}

	/**
	 * Injects the settings service
	 *
	 * @param \DERHANSEN\SfEventMgt\Service\SettingsService $settingsService The settings service
	 *
	 * @return void
	 */
	public function injectSettingsService(\DERHANSEN\SfEventMgt\Service\SettingsService $settingsService) {
		$this->settingsService = $settingsService;
	}

	/**
	 * Clears the cache of configured pages in TypoScript
	 *
	 * @param array $settings The settings
	 *
	 * @return void
	 */
	public function clearCacheForConfiguredUids($settings) {
		$pidList = $this->settingsService->getClearCacheUids($settings);
		if (count($pidList) > 0) {
			$this->cacheService->clearPageCache($pidList);
		}
	}
}