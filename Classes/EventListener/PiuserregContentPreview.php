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

final class PiuserregContentPreview extends AbstractPluginPreview
{
    #[AsEventListener('sfeventmgt/piuserreg-preview')]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' ||
            $event->getRecordType() !== 'sfeventmgt_piuserreg'
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
        $data = [];
        $flexFormData = $this->getFlexFormData($record['pi_flexform']);

        $this->setPluginPidConfig($data, $flexFormData, 'registrationPid', 'sDEF');
        $this->setStoragePage($data, $flexFormData, 'settings.userRegistration.storagePage');
        $this->setOrderSettings(
            $data,
            $flexFormData,
            'settings.userRegistration.orderField',
            'settings.userRegistration.orderDirection'
        );

        return $this->renderAsTable($request, $data);
    }
}
