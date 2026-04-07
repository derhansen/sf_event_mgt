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
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;

abstract class AbstractPluginPreview
{
    public function __construct(
        protected readonly IconFactory $iconFactory,
        protected readonly PageRenderer $pageRenderer,
        protected readonly ViewFactoryInterface $viewFactory
    ) {
        $pageRenderer->addCssFile('EXT:sf_event_mgt/Resources/Public/Css/Backend/PageLayoutView.css');
    }

    /**
     * Returns the records flexform as array
     */
    protected function getFlexFormData(string $flexform): array
    {
        $flexFormData = GeneralUtility::xml2array($flexform);
        if (!is_array($flexFormData)) {
            $flexFormData = [];
        }
        return $flexFormData;
    }

    /**
     * Renders the given data and action as HTML table for plugin preview
     */
    protected function renderAsTable(ServerRequestInterface $request, array $data): string
    {
        $template = GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Backend/PageLayoutView.fluid.html');
        $viewFactoryData = new ViewFactoryData(
            templatePathAndFilename: $template,
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assignMultiple([
            'data' => $data,
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
                'title' => (string)$this->getLanguageService()->translate('flexforms_general.' . $pidSetting, 'sf_event_mgt.be'),
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
            $recursiveLevelText = null;
            if ($recursiveLevel === 250) {
                $recursiveLevelText = $this->getLanguageService()->translate('recursive.I.5', 'frontend.ttc');
            } elseif ($recursiveLevel > 0) {
                $recursiveLevelText = $this->getLanguageService()->translate('recursive.I.' . $recursiveLevel, 'frontend.ttc');
            }

            if ($recursiveLevelText) {
                $recursiveLevelText = ' <em>(' .
                    htmlspecialchars((string)$this->getLanguageService()->translate('LGL.recursive', 'core.general')) . ' ' .
                    $recursiveLevelText . ')</em>';
            }

            $data[] = [
                'title' => $this->getLanguageService()->translate('LGL.startingpoint', 'core.general'),
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
            if ($record['CType'] === 'sfeventmgt_pieventcalendar') {
                $text .= ' <span class="badge badge-danger ms-1">' .
                    htmlspecialchars((string)$this->getLanguageService()->translate('flexforms_general.pluginCalendarMisonfiguration', 'sf_event_mgt.be')) . '</span>';
            }

            $data[] = [
                'title' => (string)$this->getLanguageService()->translate('flexforms_general.disableOverrideDemand', 'sf_event_mgt.be'),
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
            $text = (string)$this->getLanguageService()->translate('flexforms_general.orderField.' . $orderField, 'sf_event_mgt.be');

            // Order direction (asc, desc)
            $orderDirection = $this->getOrderDirectionSetting($flexFormData, $orderDirectionField);
            if ($orderDirection) {
                $text .= ', ' . strtolower($orderDirection);
            }

            $data[] = [
                'title' => (string)$this->getLanguageService()->translate('flexforms_general.orderField', 'sf_event_mgt.be'),
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
                . $this->iconFactory->getIconForRecord($table, $record, IconSize::SMALL)->render()
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
            $text = (string)$this->getLanguageService()->translate(
                'flexforms_general.orderDirection.' . $orderDirection . 'ending',
                'sf_event_mgt.be'
            );
        }

        return $text;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
