<?php
namespace DERHANSEN\SfEventMgt\Hooks;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;

/**
 * Hook for Template Layouts
 */
class TemplateLayouts {

	/**
	 * Itemsproc function to extend the selection of templateLayouts in the plugin
	 *
	 * @param array &$config configuration array
	 * @return void
	 */
	public function user_templateLayout(array &$config) {
		$templateLayouts = $this->getTemplateLayoutsFromTsConfig($config['row']['pid']);
		foreach ($templateLayouts as $index => $layout) {
			$additionalLayout = array(
				$GLOBALS['LANG']->sL($layout, TRUE),
				$index
			);
			array_push($config['items'], $additionalLayout);
		}
	}

	/**
	 * Get template layouts defined in TsConfig
	 *
	 * @param $pageUid
	 * @return array
	 */
	protected function getTemplateLayoutsFromTsConfig($pageUid) {
		$templateLayouts = array();
		$pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
		if (isset($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.']) &&
			is_array($pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'])) {
			$templateLayouts = $pagesTsConfig['tx_sfeventmgt.']['templateLayouts.'];
		}
		return $templateLayouts;
	}

}