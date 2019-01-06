<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to render the page title and indexed search title
 */
class TitleViewHelper extends AbstractViewHelper implements CompilableInterface
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
            $GLOBALS['TSFE']->altPageTitle = $pageTitle;
        }
        if ($indexedDocTitle !== '') {
            $GLOBALS['TSFE']->indexedDocTitle = $indexedDocTitle;
        }
    }
}
