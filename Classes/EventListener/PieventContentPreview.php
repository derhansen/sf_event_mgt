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
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class PieventContentPreview extends AbstractPluginPreview
{
    private array $configuredPlugins = [
        'sfeventmgt_pieventlist',
        'sfeventmgt_pieventdetail',
        'sfeventmgt_pieventregistration',
        'sfeventmgt_pieventsearch',
        'sfeventmgt_pieventcalendar',
    ];

    #[AsEventListener('sfeventmgt/pievent-preview')]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' ||
            !in_array($event->getRecordType(), $this->configuredPlugins, true)
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

        $this->setPluginPidConfig($data, $flexFormData, 'listPid', 'additional');
        $this->setPluginPidConfig($data, $flexFormData, 'detailPid', 'additional');
        $this->setPluginPidConfig($data, $flexFormData, 'registrationPid', 'additional');
        $this->setPluginPidConfig($data, $flexFormData, 'paymentPid', 'additional');

        $this->setStoragePage($data, $flexFormData, 'settings.storagePage');

        $this->setOrderSettings($data, $flexFormData, 'settings.orderField', 'settings.orderDirection');
        $this->setOverrideDemandSettings($data, $flexFormData, $record);

        $this->setCategoryConjuction($data, $flexFormData);
        $this->setCategorySettings($data, $flexFormData);

        return $this->renderAsTable($request, $data);
    }

    /**
     * Sets category conjunction if a category is selected
     */
    private function setCategoryConjuction(array &$data, array $flexFormData): void
    {
        // If not category is selected, we do not need to display the category mode
        $categories = $this->getFlexFormFieldValue($flexFormData, 'settings.category');
        if ($categories === null || $categories === '') {
            return;
        }

        $categoryConjunction = strtolower($this->getFlexFormFieldValue($flexFormData, 'settings.categoryConjunction') ?? '');
        switch ($categoryConjunction) {
            case 'or':
            case 'and':
            case 'notor':
            case 'notand':
                $text = htmlspecialchars($this->getLanguageService()->sL(
                    self::LLPATH . 'flexforms_general.categoryConjunction.' . $categoryConjunction
                ));
                break;
            default:
                $text = htmlspecialchars($this->getLanguageService()->sL(
                    self::LLPATH . 'flexforms_general.categoryConjunction.ignore'
                ));
                $text .= ' <span class="badge badge-warning">' . htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.possibleMisconfiguration')) . '</span>';
        }

        $data[] = [
            'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.categoryConjunction'),
            'value' => $text,
        ];
    }

    /**
     * Get category settings
     */
    private function setCategorySettings(array &$data, array $flexFormData): void
    {
        $categories = GeneralUtility::intExplode(',', $this->getFlexFormFieldValue($flexFormData, 'settings.category'), true);
        if (count($categories) > 0) {
            $categoriesOut = [];
            foreach ($categories as $id) {
                $categoriesOut[] = $this->getRecordData($id, 'sys_category');
            }

            $data[] = [
                'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.category'),
                'value' => implode(', ', $categoriesOut),
            ];

            $includeSubcategories = $this->getFlexFormFieldValue($flexFormData, 'settings.includeSubcategories');
            if ((int)$includeSubcategories === 1) {
                $data[] = [
                    'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.includeSubcategories'),
                    'value' => '',
                    'icon' => 'actions-check-square',
                ];
            }
        }
    }
}
