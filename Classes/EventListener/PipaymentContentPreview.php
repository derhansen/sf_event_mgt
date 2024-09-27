<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;

final class PipaymentContentPreview extends AbstractPluginPreview
{
    #[AsEventListener('sfeventmgt/pipayment-preview')]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' ||
            $event->getRecordType() !== 'list' ||
            $event->getRecord()['list_type'] !== 'sfeventmgt_pipayment'
        ) {
            return;
        }

        $previewContent = $this->renderPreviewContent(
            $event->getRecord(),
            $event->getPageLayoutContext()->getCurrentRequest()
        );
        $event->setPreviewContent($previewContent);
    }

    private function renderPreviewContent(array $record, ServerRequestInterface $request): string
    {
        $pluginName = $this->getPluginName($record);

        return $this->renderAsTable($request, [], $pluginName);
    }
}
