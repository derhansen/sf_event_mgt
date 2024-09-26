<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class FluidStandaloneService
{
    protected ConfigurationManagerInterface $configurationManager;

    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
    {
        $this->configurationManager = $configurationManager;
    }

    /**
     * Returns the template folders for the given part
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
     */
    protected function ensureSuffixedPath(string $path): string
    {
        return rtrim($path, '/') . '/';
    }

    /**
     * Renders a fluid standalone view for the given template
     */
    public function renderTemplate(
        RequestInterface $request,
        string $template,
        array $variables,
        string $extensionName = 'SfEventMgt',
        string $pluginName = 'Pieventregistration',
        string $format = 'html'
    ): string {
        $emailView = GeneralUtility::makeInstance(StandaloneView::class);

        $extbaseRequestParams = GeneralUtility::makeInstance(ExtbaseRequestParameters::class);
        $extbaseRequestParams->setControllerExtensionName($extensionName);
        $extbaseRequestParams->setPluginName($pluginName);

        $emailView->setRequest($request);

        $emailView->setFormat($format);
        $emailView->setTemplateRootPaths($this->getTemplateFolders());
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
     */
    public function parseStringFluid(RequestInterface $request, string $string, array $variables = []): string
    {
        if ($string === '') {
            return '';
        }

        $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $standaloneView->setTemplateSource($string);
        $standaloneView->assignMultiple($variables);
        $standaloneView->setRequest($request);
        $result = $standaloneView->render() ?? '';

        return html_entity_decode($result);
    }
}
