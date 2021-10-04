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
     * Renders the header
     *
     * @param GridColumnItem $item
     * @return string
     */
    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $label = BackendUtility::getLabelFromItemListMerged(
            $record['pid'],
            'tt_content',
            'list_type',
            $record['list_type']
        );
        return '<strong>' . htmlspecialchars($this->getLanguageService()->sL($label)) . '</strong> <br/>';
    }

    /**
     * Renders the content of the plugin preview. Must be overwritten in extending class.
     *
     * @param GridColumnItem $item
     * @return string
     */
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * Render the footer. Can be overwritten in extending class if required
     *
     * @param GridColumnItem $item
     * @return string
     */
    public function renderPageModulePreviewFooter(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * Render the plugin preview
     *
     * @param string $previewHeader
     * @param string $previewContent
     * @param GridColumnItem $item
     * @return string
     */
    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        return $previewHeader . $previewContent;
    }

    /**
     * Renders the given data and action as HTML table for plugin preview
     *
     * @param array $data
     * @param string $action
     * @return string
     */
    protected function renderAsTable(array $data, string $action = ''): string
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Backend/PageLayoutView.html')
        );
        $view->assignMultiple([
            'data' => $data,
            'action' => $action,
        ]);

        return $view->render();
    }

    /**
     * Sets the PID config for the configured PID settings in plugin flexform
     *
     * @param array $data
     * @param array $flexFormData
     * @param string $pidSetting
     * @param string $sheet
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
     *
     * @param array $data
     * @param array $flexFormData
     * @param string $field
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
     *
     * @param array $data
     * @param array $flexFormData
     */
    protected function setOverrideDemandSettings(array &$data, array $flexFormData): void
    {
        $field = (int)$this->getFlexFormFieldValue($flexFormData, 'settings.disableOverrideDemand', 'additional');

        if ($field === 1) {
            $text = '<i class="fa fa-check"></i>';

            // Check if plugin action is "calendar" and if so, show warning that calendar action will not work
            $action = $this->getFlexFormFieldValue($flexFormData, 'switchableControllerActions');
            if ($action === 'Event->calendar') {
                $text .= ' <span class="label label-danger">' .
                    htmlspecialchars($this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.pluginCalendarMisonfiguration')) . '</span>';
            }

            $data[] = [
                'title' => $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.disableOverrideDemand'),
                'value' => $text,
            ];
        }
    }

    /**
     * Sets the order settings
     *
     * @param array $data
     * @param array $flexFormData
     * @param string $orderByField
     * @param string $orderDirectionField
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
     * Returns the current title of the switchableControllerAction
     *
     * @param array $flexFormData
     * @return string
     */
    protected function getSwitchableControllerActionTitle(array $flexFormData): string
    {
        $title = '';
        $actions = $this->getFlexFormFieldValue($flexFormData, 'switchableControllerActions');
        switch ($actions) {
            case 'Event->list':
                $title = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.list');
                break;
            case 'Event->detail;Event->icalDownload':
                $title = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.detail');
                break;
            case 'Event->registration;Event->saveRegistration;Event->saveRegistrationResult;Event->confirmRegistration;Event->cancelRegistration':
                $title = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.registration');
                break;
            case 'Event->search':
                $title = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.search');
                break;
            case 'Event->calendar':
                $title = $this->getLanguageService()->sL(self::LLPATH . 'flexforms_general.mode.calendar');
                break;
            default:
        }

        return $title;
    }

    /**
     * Returns field value from flexform configuration, including checks if flexform configuration is available
     *
     * @param string $key name of the key
     * @param string $sheet name of the sheet
     * @return string|null if nothing found, value if found
     */
    protected function getFlexFormFieldValue(array $flexformData, string $key, string $sheet = 'sDEF'): ?string
    {
        if (isset($flexformData['data'])) {
            $flexform = $flexformData['data'];
            if (is_array($flexform ?? false) && is_array($flexform[$sheet] ?? false) &&
                is_array($flexform[$sheet]['lDEF'] ?? false) && is_array($flexform[$sheet]['lDEF'][$key] ?? false) &&
                isset($flexform[$sheet]['lDEF'][$key]['vDEF'])
            ) {
                return $flexform[$sheet]['lDEF'][$key]['vDEF'];
            }
        }

        return null;
    }

    /**
     * Returns the record data item
     *
     * @param int $id
     * @param string $table
     * @return string
     */
    protected function getRecordData(int $id, string $table = 'pages'): string
    {
        $content = '';
        $record = BackendUtility::getRecord($table, $id);

        if (is_array($record)) {
            $data = '<span data-toggle="tooltip" data-placement="top" data-title="id=' . $record['uid'] . '">'
                . $this->iconFactory->getIconForRecord($table, $record, Icon::SIZE_SMALL)->render()
                . '</span> ';
            $content = BackendUtility::wrapClickMenuOnIcon($data, $table, $record['uid'], '', '', '+info');

            $linkTitle = htmlspecialchars(BackendUtility::getRecordTitle($table, $record));
            $content .= $linkTitle;
        }

        return $content;
    }

    /**
     * Returns order direction
     *
     * @param array $flexFormData
     * @param string $orderDirectionField
     * @return string
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
