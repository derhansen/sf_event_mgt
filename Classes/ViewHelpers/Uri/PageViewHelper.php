<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

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

use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Modified version of TYPO3 f:uri.page viewHelper, which always generates frontend URLs, so views created in
 * backend context can render links in frontend context
 */
class PageViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('pageUid', 'int', 'target PID');
        $this->registerArgument('additionalParams', 'array', 'query parameters to be attached to the resulting URI', false, []);
        $this->registerArgument('pageType', 'int', 'type of the target page. See typolink.parameter', false, 0);
        $this->registerArgument('noCache', 'bool', 'set this to disable caching for the target page. You should not need this.', false, false);
        $this->registerArgument('section', 'string', 'the anchor to be added to the URI', false, '');
        $this->registerArgument('linkAccessRestrictedPages', 'bool', 'If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.', false, false);
        $this->registerArgument('absolute', 'bool', 'If set, the URI of the rendered link is absolute', false, false);
        $this->registerArgument('addQueryString', 'bool', 'If set, the current query parameters will be kept in the URI', false, false);
        $this->registerArgument('argumentsToBeExcludedFromQueryString', 'array', 'arguments to be removed from the URI. Only active if $addQueryString = TRUE', false, []);
        $this->registerArgument('addQueryStringMethod', 'string', 'Set which parameters will be kept. Only active if $addQueryString = TRUE');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string Rendered page URI
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $pageUid = $arguments['pageUid'];
        $additionalParams = $arguments['additionalParams'];
        $pageType = $arguments['pageType'];
        $noCache = $arguments['noCache'];
        $section = $arguments['section'];
        $linkAccessRestrictedPages = $arguments['linkAccessRestrictedPages'];
        $absolute = $arguments['absolute'];
        $addQueryString = $arguments['addQueryString'];
        $argumentsToBeExcludedFromQueryString = $arguments['argumentsToBeExcludedFromQueryString'];
        $addQueryStringMethod = $arguments['addQueryStringMethod'];

        /** @var UriBuilder $uriBuilder */
        $uriBuilder = $renderingContext->getControllerContext()->getUriBuilder();
        $uri = $uriBuilder
            ->reset()
            ->setTargetPageType($pageType)
            ->setNoCache($noCache)
            ->setSection($section)
            ->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
            ->setArguments($additionalParams)
            ->setCreateAbsoluteUri($absolute)
            ->setAddQueryString($addQueryString)
            ->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
        ;

        if (MathUtility::canBeInterpretedAsInteger($pageUid)) {
            $uriBuilder->setTargetPageUid((int)$pageUid);
        }

        if (is_string($addQueryStringMethod)) {
            $uriBuilder->setAddQueryStringMethod($addQueryStringMethod);
        }

        return $uri->buildFrontendUri();
    }
}
