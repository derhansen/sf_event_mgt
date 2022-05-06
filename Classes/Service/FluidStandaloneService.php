<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * FluidStandaloneService
 */
class FluidStandaloneService
{
    /**
     * The configuration manager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     */
    protected $configurationManager;

    /**
     * DI for $configurationManager
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
     */
    public function injectConfigurationManager(
        \TYPO3\CMS\Extbase\Configuration\ConfigurationManager $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns the template folders for the given part
     *
     * @param string $part
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @return array
     */
    public function getTemplateFolders($part = 'template')
    {
        $extbaseConfig = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'SfEventMgt'
        );

        if (!empty($extbaseConfig['view'][$part . 'RootPaths'])) {
            $templatePaths = $extbaseConfig['view'][$part . 'RootPaths'];
            ksort($templatePaths);
        }
        if (empty($templatePaths)) {
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
    protected function ensureSuffixedPath($path)
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
    public function renderTemplate($template, $variables, $extensionName = 'SfEventMgt', $pluginName = 'Pievent')
    {
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);
        $emailView->getRequest()->setControllerExtensionName($extensionName);
        $emailView->getRequest()->setPluginName($pluginName);
        $emailView->setFormat('html');
        $emailView->setTemplateRootPaths($this->getTemplateFolders('template'));
        $emailView->setLayoutRootPaths($this->getTemplateFolders('layout'));
        $emailView->setPartialRootPaths($this->getTemplateFolders('partial'));
        $emailView->setTemplate($template);
        $emailView->assignMultiple($variables);
        $emailBody = $emailView->render();

        return $emailBody;
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
