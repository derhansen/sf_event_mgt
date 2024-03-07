<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use Closure;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * ViewHelper to render meta tags
 */
class MetaTagViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    public function initializeArguments(): void
    {
        $this->registerArgument('property', 'string', 'Property of meta tag', false, '', false);
        $this->registerArgument('name', 'string', 'Content of meta tag using the name attribute', false, '', false);
        $this->registerArgument('content', 'string', 'Content of meta tag', true, null, false);
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        // Skip if current record is part of tt_content CType shortcut
        if (!empty($GLOBALS['TSFE']->recordRegister)
            && !empty($GLOBALS['TSFE']->currentRecord)
            && is_array($GLOBALS['TSFE']->recordRegister)
            && str_contains($GLOBALS['TSFE']->currentRecord, 'tx_sfeventmgt_domain_model_event:')
            && str_contains(array_keys($GLOBALS['TSFE']->recordRegister)[0], 'tt_content:')
        ) {
            return;
        }

        $content = (string)$arguments['content'];

        if ($content !== '') {
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            if ($arguments['property']) {
                $pageRenderer->setMetaTag('property', $arguments['property'], $content);
            } elseif ($arguments['name']) {
                $pageRenderer->setMetaTag('property', $arguments['name'], $content);
            }
        }
    }
}
