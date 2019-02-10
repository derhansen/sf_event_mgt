<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\TimeTracker\TimeTracker;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * A viewhelper with the same functionality as the f:uri.page viewhelper,
 * but this viewhelper builds frontend links with buildFrontendUri, so links
 * to FE pages can get generated in the TYPO3 backend
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var int
     */
    protected $pid = 0;

    /**
     * Creates a TSFE object which can be used in Backend
     *
     * @return void
     */
    protected function buildTsfe()
    {
        if (!is_object($GLOBALS['TT'])) {
            $GLOBALS['TT'] = $this->getTimeTrackerInstance();
            $GLOBALS['TT']->start();
        }
        $GLOBALS['TSFE'] = $this->getTsfeInstance();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->fetch_the_id();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
    }

    /**
     * Returns a new instance of the TypoScriptFrontendController
     *
     * @return object
     */
    protected function getTsfeInstance()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            $this->pid,
            '0',
            1,
            '',
            '',
            '',
            ''
        );
    }

    /**
     * Returns a new instance of TimeTracker
     *
     * @return object
     */
    protected function getTimeTrackerInstance()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TimeTracker::class);
    }

    /**
     * @param int|null $pageUid target PID
     * @param array $additionalParams query parameters to be attached to the resulting URI
     * @param int $pageType type of the target page. See typolink.parameter
     * @param bool $noCache set this to disable caching for the target page. You should not need this.
     * @param bool $noCacheHash set this to supress the cHash query parameter created by TypoLink. You should not need this.
     * @param string $section the anchor to be added to the URI
     * @param bool $linkAccessRestrictedPages if set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed
     * @param bool $absolute If set, the URI of the rendered link is absolute
     * @param bool $addQueryString If set, the current query parameters will be kept in the URI
     * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
     * @param string $addQueryStringMethod Set which parameters will be kept. Only active if $addQueryString = TRUE
     * @return string Rendered page URI
     */
    public function render(
        $pageUid = null,
        array $additionalParams = [],
        $pageType = 0,
        $noCache = false,
        $noCacheHash = false,
        $section = '',
        $linkAccessRestrictedPages = false,
        $absolute = false,
        $addQueryString = false,
        array $argumentsToBeExcludedFromQueryString = [],
        $addQueryStringMethod = null
    ) {
        $this->buildTsfe();
        $uriBuilder = $this->controllerContext->getUriBuilder();
        $uri = $uriBuilder->setTargetPageUid($pageUid)->setTargetPageType($pageType)->setNoCache($noCache)->setUseCacheHash(!$noCacheHash)->setSection($section)->setLinkAccessRestrictedPages($linkAccessRestrictedPages)->setArguments($additionalParams)->setCreateAbsoluteUri($absolute)->setAddQueryString($addQueryString)->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)->setAddQueryStringMethod($addQueryStringMethod)->buildFrontendUri();

        return $uri;
    }
}
