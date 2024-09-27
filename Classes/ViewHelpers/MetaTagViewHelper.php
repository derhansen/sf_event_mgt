<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render meta tags
 */
class MetaTagViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('property', 'string', 'Property of meta tag', false, '', false);
        $this->registerArgument('name', 'string', 'Content of meta tag using the name attribute', false, '', false);
        $this->registerArgument('content', 'string', 'Content of meta tag', true, null, false);
    }

    public function render(): void
    {
        $tsfe = $this->getTypoScriptFrontendController();

        // Skip if current record is part of tt_content CType shortcut
        if (!empty($tsfe->recordRegister)
            && !empty($tsfe->currentRecord)
            && str_contains($tsfe->currentRecord, 'tx_sfeventmgt_domain_model_event:')
            && str_contains(array_keys($tsfe->recordRegister)[0], 'tt_content:')
        ) {
            return;
        }

        $content = (string)$this->arguments['content'];

        if ($content !== '') {
            $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
            if ($this->arguments['property']) {
                $pageRenderer->setMetaTag('property', $this->arguments['property'], $content);
            } elseif ($this->arguments['name']) {
                $pageRenderer->setMetaTag('property', $this->arguments['name'], $content);
            }
        }
    }

    private function getTypoScriptFrontendController(): TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'];
    }
}
