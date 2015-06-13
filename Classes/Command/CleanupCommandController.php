<?php
namespace DERHANSEN\SfEventMgt\Command;

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

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class CleanupCommandController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class CleanupCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * Configurationmanager
	 *
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Cacheservice
	 *
	 * @var \TYPO3\CMS\Extbase\Service\CacheService
	 * @inject
	 */
	protected $cacheService;

	/**
	 * Registrationservice
	 *
	 * @var \DERHANSEN\SfEventMgt\Service\RegistrationService
	 * @inject
	 */
	protected $registrationService;

	/**
	 * The cleanup command
	 *
	 * @return void
	 */
	public function cleanupCommand() {
		$fullSettings = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
			'SfEventMgt',
			'Pievent'
		);

		$settings = $fullSettings['plugin.']['tx_sfeventmgt.']['settings.'];
		$this->registrationService->handleExpiredRegistrations(
			$settings['registration.']['deleteExpiredRegistrations']);

		$pidList = explode(',', $settings['clearCacheUids']);
		$this->cacheService->clearPageCache($pidList);
	}
}