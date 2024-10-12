<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\MetaTag\MetaTagManagerRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
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
        $this->registerArgument('replace', 'boolean', 'Replace potential existing tag', false, false);
    }

    public function render(): void
    {
        // Skip if current record is rendered via tt_content CType shortcut
        if ($this->isParentRecordShortcut()) {
            return;
        }

        $content = (string)$this->arguments['content'];

        if ($content !== '') {
            $registry = GeneralUtility::makeInstance(MetaTagManagerRegistry::class);
            if ($this->arguments['property']) {
                $manager = $registry->getManagerForProperty($this->arguments['property']);
                $manager->addProperty($this->arguments['property'], $content, [], $this->arguments['replace'], 'property');
            } elseif ($this->arguments['name']) {
                $manager = $registry->getManagerForProperty($this->arguments['name']);
                $manager->addProperty($this->arguments['name'], $content, [], $this->arguments['replace'], 'name');
            }
        }
    }

    private function isParentRecordShortcut(): bool
    {
        $contentObjectRenderer = $this->getContentObjectRenderer();

        return $contentObjectRenderer->parentRecord !== [] &&
            isset($contentObjectRenderer->parentRecord['currentRecord']) &&
            isset($contentObjectRenderer->parentRecord['data']['CType']) &&
            str_starts_with($contentObjectRenderer->parentRecord['currentRecord'], 'tt_content:') &&
            $contentObjectRenderer->parentRecord['data']['CType'] === 'shortcut';
    }

    private function getContentObjectRenderer(): ContentObjectRenderer
    {
        return $this->renderingContext->getAttribute(ServerRequestInterface::class)
            ->getAttribute('currentContentObject');
    }
}
