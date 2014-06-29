<?php
namespace SKYFILLERS\SfEventMgt\Service;

	/***************************************************************
	 *
	 *  Copyright notice
	 *
	 *  (c) 2014 Torben Hansen <derhansen@gmail.com>, Skyfillers GmbH
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

/**
 * SettingsService
 */
class SettingsService {

	/**
	 * Returns an array of page uids for which the cache should be cleared
	 *
	 * @param $settings
	 * @return array
	 */
	public function getClearCacheUids($settings) {
		$clearCacheUids = $settings['clearCacheUids'];

		if (is_int($settings['detailPid'])) {
			$clearCacheUids .= ',' . $settings['detailPid'];
		}

		if (is_int($settings['listPid'])) {
			$clearCacheUids .= ',' . $settings['listPid'];
		}

		if ($clearCacheUids == NULL) {
			return array();
		}
		$return = preg_split('/,/', $clearCacheUids, NULL, PREG_SPLIT_NO_EMPTY);
		return $return;
	}
}