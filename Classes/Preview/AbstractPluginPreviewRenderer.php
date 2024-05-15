<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

abstract class AbstractPluginPreviewRenderer implements PreviewRendererInterface
{
    protected const LLPATH = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:';

    protected IconFactory $iconFactory;

    public function __construct()
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addCssFile('EXT:sf_event_mgt/Resources/Public/Css/Backend/PageLayoutView.css');
    }

    /**
     * Renders the header (actually empty, since header is rendered in content)
     */
    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * Renders the content of the plugin preview. Must be overwritten in extending class.
     */
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * Render the footer. Can be overwritten in extending class if required
     */
    public function renderPageModulePreviewFooter(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * Render the plugin preview
     */
    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        return $previewHeader . $previewContent;
    }

    /**
     * Returns the plugin name
     */
    protected function getPluginName(array $record): string
    {
        $pluginId = str_replace('sfeventmgt_', '', $record['list_type']);
        return htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'plugin.' . $pluginId . '.title'));
    }

    /**
     * Renders the given data and action as HTML table for plugin preview
     */
    protected function renderAsTable(array $data, string $pluginName = ''): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Backend/PageLayoutView.html')
        );
        $view->assignMultiple([
            'data' => $data,
            'pluginName' => $pluginName,
        ]);

        return $view->render();
    }

    /**
     * Sets the PID config for the configured PID settings in plugin flexform
     */
    protected function setPluginPidConfig(
        array &$data,
        array $flexFormData,
        string $pidSetting,
        string $sheet = 'sDEF'
    ): void {
        $pid = (int)$this->getFlexFormFieldValue($flexFormData, 'settings.' . $pidSetting, $sheet);
        if ($pid > 0) {
            $data[] = [
                'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.' . $pidSetting),
                'value' => $this->getRecordData($pid),
            ];
        }
    }

    /**
     * Sets the storagePage configuration
     */
    protected function setStoragePage(array &$data, array $flexFormData, string $field): void
    {
        $value = $this->getFlexFormFieldValue($flexFormData, $field);

        if (!empty($value)) {
            $pageIds = GeneralUtility::intExplode(',', $value, true);
            $pagesOut = [];

            foreach ($pageIds as $id) {
                $pagesOut[] = $this->getRecordData($id, 'pages');
            }

            $recursiveLevel = (int)$this->getFlexFormFieldValue($flexFormData, 'settings.recursive');
            $recursiveLevelText = '';
            if ($recursiveLevel === 250) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.5');
            } elseif ($recursiveLevel > 0) {
                $recursiveLevelText = $this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:recursive.I.' . $recursiveLevel);
            }

            if (!empty($recursiveLevelText)) {
                $recursiveLevelText = '<br />' .
                    htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.recursive')) . ' ' .
                    $recursiveLevelText;
            }

            $data[] = [
                'title' => $this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.startingpoint'),
                'value' => implode(', ', $pagesOut) . $recursiveLevelText,
            ];
        }
    }

    /**
     * Sets information to the data array if override demand setting is disabled
     */
    protected function setOverrideDemandSettings(array &$data, array $flexFormData, array $record): void
    {
        $field = (int)$this->getFlexFormFieldValue($flexFormData, 'settings.disableOverrideDemand', 'additional');

        if ($field === 1) {
            $text = '';

            // Check if plugin action is "calendar" and if so, show warning that calendar action will not work
            if ($record['list_type'] === 'sfeventmgt_pieventcalendar') {
                $text .= ' <span class="badge badge-danger ms-1">' .
                    htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.pluginCalendarMisonfiguration')) . '</span>';
            }

            $data[] = [
                'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.disableOverrideDemand'),
                'value' => $text,
                'icon' => 'actions-check-square',
            ];
        }
    }

    /**
     * Sets the order settings
     */
    protected function setOrderSettings(
        array &$data,
        array $flexFormData,
        string $orderByField,
        string $orderDirectionField
    ): void {
        $orderField = $this->getFlexFormFieldValue($flexFormData, $orderByField);
        if (!empty($orderField)) {
            $text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderField.' . $orderField);

            // Order direction (asc, desc)
            $orderDirection = $this->getOrderDirectionSetting($flexFormData, $orderDirectionField);
            if ($orderDirection) {
                $text .= ', ' . strtolower($orderDirection);
            }

            $data[] = [
                'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderField'),
                'value' => $text,
            ];
        }
    }

    /**
     * Returns field value from flexform configuration, including checks if flexform configuration is available
     */
    protected function getFlexFormFieldValue(array $flexformData, string $key, string $sheet = 'sDEF'): ?string
    {
        return $flexformData['data'][$sheet]['lDEF'][$key]['vDEF'] ?? '';
    }

    /**
     * Returns the record data item
     */
    protected function getRecordData(int $id, string $table = 'pages'): string
    {
        $content = '';
        $record = BackendUtility::getRecord($table, $id);

        if (is_array($record)) {
            $data = '<span data-toggle="tooltip" data-placement="top" data-title="id=' . $record['uid'] . '">'
                . $this->iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render()
                . '</span> ';
            $content = BackendUtility::wrapClickMenuOnIcon($data, $table, $record['uid'], '', $record);

            $linkTitle = htmlspecialchars(BackendUtility::getRecordTitle($table, $record));
            $content .= $linkTitle;
        }

        return $content;
    }

    /**
     * Returns order direction
     */
    private function getOrderDirectionSetting(array $flexFormData, string $orderDirectionField): string
    {
        $text = '';

        $orderDirection = $this->getFlexFormFieldValue($flexFormData, $orderDirectionField);
        if (!empty($orderDirection)) {
            $text = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.orderDirection.' . $orderDirection . 'ending');
        }

        return $text;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
