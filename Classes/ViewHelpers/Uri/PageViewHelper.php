<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

/**
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
 * A viewhelper with the same functionality as the f:uri.page viewhelper,
 * but this viewhelper builds frontend links with buildFrontendUri, so links
 * to FE pages can get generated in the TYPO3 backend
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Creates a TSFE object which can be used in Backend
	 *
	 * @return void
	 */
	protected function buildTsfe() {
		if (!is_object($GLOBALS['TT'])) {
			$GLOBALS['TT'] = $this->getTimeTrackerInstance();
			$GLOBALS['TT']->start();
		}
		$GLOBALS['TSFE'] = $this->getTsfeInstance();
		$GLOBALS['TSFE']->initFEuser();
		$GLOBALS['TSFE']->fetch_the_id();
		$GLOBALS['TSFE']->getPageAndRootline();
		$GLOBALS['TSFE']->initTemplate();
		$GLOBALS['TSFE']->getConfigArray();
	}

	/**
	 * Returns a new instance of the TypoScriptFrontendController
	 *
	 * @return object
	 */
	protected function getTsfeInstance() {
		$tsfeClassname = 'TYPO3\\CMS\\Frontend\\Controller\\TypoScriptFrontendController';
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($tsfeClassname,
			$GLOBALS['TYPO3_CONF_VARS'], $this->pid, '0', 1, '', '', '', '');
	}

	/**
	 * Returns a new instance of TimeTracker
	 *
	 * @return object
	 */
	protected function getTimeTrackerInstance() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TimeTracker\\TimeTracker');
	}

	/**
	 * @param integer|NULL $pageUid target PID
	 * @param array $additionalParams query parameters to be attached to the resulting URI
	 * @param integer $pageType type of the target page. See typolink.parameter
	 * @param boolean $noCache set this to disable caching for the target page. You should not need this.
	 * @param boolean $noCacheHash set this to supress the cHash query parameter created by TypoLink. You should not need this.
	 * @param string $section the anchor to be added to the URI
	 * @param boolean $linkAccessRestrictedPages If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
	 * @param boolean $absolute If set, the URI of the rendered link is absolute
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param string $addQueryStringMethod Set which parameters will be kept. Only active if $addQueryString = TRUE
	 * @return string Rendered page URI
	 */
	public function render($pageUid = NULL, array $additionalParams = array(), $pageType = 0, $noCache = FALSE, $noCacheHash = FALSE, $section = '', $linkAccessRestrictedPages = FALSE, $absolute = FALSE, $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $addQueryStringMethod = NULL) {
		$this->buildTsfe();
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder->setTargetPageUid($pageUid)->setTargetPageType($pageType)->setNoCache($noCache)->setUseCacheHash(!$noCacheHash)->setSection($section)->setLinkAccessRestrictedPages($linkAccessRestrictedPages)->setArguments($additionalParams)->setCreateAbsoluteUri($absolute)->setAddQueryString($addQueryString)->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)->setAddQueryStringMethod($addQueryStringMethod)->buildFrontendUri();
		return $uri;
	}
} 