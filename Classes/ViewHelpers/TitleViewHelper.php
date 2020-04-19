<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use DERHANSEN\SfEventMgt\PageTitle\EventPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to render the page title and indexed search title
 */
class TitleViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('pageTitle', 'String', 'The page title');
        $this->registerArgument(
            'indexedDocTitle',
            'string',
            'The title for the event in indexed search result',
            false
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $pageTitle = isset($arguments['pageTitle']) ? $arguments['pageTitle'] : '';
        $indexedDocTitle = isset($arguments['indexedDocTitle']) ? $arguments['indexedDocTitle'] : $pageTitle;
        if ($pageTitle !== '') {
            GeneralUtility::makeInstance(EventPageTitleProvider::class)->setTitle($pageTitle);
        }
        if ($indexedDocTitle !== '') {
            // Unsure if this is still required in TYPO3 10
            $GLOBALS['TSFE']->indexedDocTitle = $indexedDocTitle;
        }
    }
}
