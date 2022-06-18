<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * FluidStandaloneService
 */
class FluidStandaloneService
{
    protected ConfigurationManager $configurationManager;

    public function injectConfigurationManager(ConfigurationManager $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns the template folders for the given part
     *
     * @param string $part
     * @throws InvalidConfigurationTypeException
     * @return array
     */
    public function getTemplateFolders(string $part = 'template'): array
    {
        $extbaseConfig = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'SfEventMgt'
        );

        if (!empty($extbaseConfig['view'][$part . 'RootPaths'])) {
            $templatePaths = $extbaseConfig['view'][$part . 'RootPaths'];
            ksort($templatePaths);
        }
        if (empty($templatePaths) && isset($extbaseConfig['view'])) {
            $path = $extbaseConfig['view'][$part . 'RootPath'];
            if (!empty($path)) {
                $templatePaths = [];
                $templatePaths[] = $path;
            }
        }
        if (empty($templatePaths)) {
            $templatePaths = [];
            $templatePaths[] = 'EXT:sf_event_mgt/Resources/Private/' . ucfirst($part) . 's/';
        }

        $absolutePaths = [];
        foreach ($templatePaths as $templatePath) {
            $absolutePaths[] = GeneralUtility::getFileAbsFileName($this->ensureSuffixedPath($templatePath));
        }

        return $absolutePaths;
    }

    /**
     * Makes sure the path ends with a slash
     *
     * @param string $path
     * @return string
     */
    protected function ensureSuffixedPath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Renders a fluid standlone view for the given template
     *
     * @param string $template
     * @param array $variables
     * @param string $extensionName
     * @param string $pluginName
     * @return string
     */
    public function renderTemplate(
        string $template,
        array $variables,
        string $extensionName = 'SfEventMgt',
        string $pluginName = 'Pieventregistration'
    ): string {
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $emailView->getRequest()->setControllerExtensionName($extensionName);
        $emailView->getRequest()->setPluginName($pluginName);
        $emailView->setFormat('html');
        $emailView->setTemplateRootPaths($this->getTemplateFolders('template'));
        $emailView->setLayoutRootPaths($this->getTemplateFolders('layout'));
        $emailView->setPartialRootPaths($this->getTemplateFolders('partial'));
        $emailView->setTemplate($template);
        $emailView->assignMultiple($variables);
        return $emailView->render();
    }

    /**
     * Parses the given string with Fluid View and decodes the result with html_entity_decode to revert Fluids encoding
     * of variables.
     *
     * Note, the result of this function must never be used as raw/direct output in HTML/frontend context.
     *
     * @param string $string Any string
     * @param array $variables Variables
     * @return string Parsed string
     */
    public function parseStringFluid(string $string, array $variables = []): string
    {
        if ($string === '') {
            return '';
        }
        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplateSource($string);
        $standaloneView->assignMultiple($variables);
        $result = $standaloneView->render() ?? '';

        return html_entity_decode($result);
    }
}
