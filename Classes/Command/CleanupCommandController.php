<?php
namespace DERHANSEN\SfEventMgt\Command;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Torben Hansen <derhansen@gmail.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Class CleanupCommandController
 */
class CleanupCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\CacheService
	 * @inject
	 */
	protected $cacheService;

	/**
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