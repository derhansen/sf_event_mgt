<?php
namespace SKYFILLERS\SfEventMgt\ViewHelpers;

/***************************************************************
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Prefill ViewHelper
 *
 * Usage:
 *
 * {e:prefill(fieldname: fieldname, prefillSettings: '{settings.registration.prefillFields}')}
 *
 */
class PrefillViewHelper extends AbstractViewHelper {

	/**
	 * Returns a property from fe_user (if logged in and if the given field is
	 * configured to be prefilled)
	 *
	 * @param string $fieldname
	 * @param array $prefillSettings
	 * @return string
	 */
	public function render($fieldname, $prefillSettings = array()) {
		if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->loginUser || empty($prefillSettings) ||
			!array_key_exists($fieldname, $prefillSettings)) {
			return '';
		}
		return strval($GLOBALS['TSFE']->fe_user->user[$prefillSettings[$fieldname]]);
	}

}
