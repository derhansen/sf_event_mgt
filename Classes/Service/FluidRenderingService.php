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
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Fluid\View\FluidViewAdapter;

class FluidRenderingService
{
    public function __construct(
        private readonly ConfigurationManagerInterface $configurationManager,
        private readonly ViewFactoryInterface $viewFactory
    ) {
    }

    /**
     * Renders a Fluid view for the given template
     */
    public function renderTemplate(
        RequestInterface $request,
        string $template,
        array $variables,
        string $format = 'html'
    ): string {
        $viewFactoryData = new ViewFactoryData(
            templateRootPaths: $this->getTemplateFolders(),
            partialRootPaths: $this->getTemplateFolders('partial'),
            layoutRootPaths: $this->getTemplateFolders('layout'),
            request: $request,
            format: $format
        );
        $view = $this->viewFactory->create($viewFactoryData);
        $view->assignMultiple($variables);
        return $view->render($template);
    }

    /**
     * Parses the given string with Fluid and decodes the result with html_entity_decode to revert Fluids encoding
     * of variables.
     *
     * Note, the result of this function must never be used as raw/direct output in HTML/frontend context.
     */
    public function parseString(RequestInterface $request, string $string, array $variables = []): string
    {
        if ($string === '') {
            return '';
        }

        $viewFactoryData = new ViewFactoryData(
            request: $request,
        );
        $view = $this->viewFactory->create($viewFactoryData);
        if (!$view instanceof FluidViewAdapter) {
            throw new \RuntimeException(
                'FluidRenderingService->parseStringFluid() can only deal with Fluid views via FluidViewAdapter',
                1727434457
            );
        }
        $view->getRenderingContext()->getTemplatePaths()->setTemplateSource($string);
        $view->assignMultiple($variables);

        return html_entity_decode($view->render());
    }

    /**
     * Returns the template folders for the given part
     */
    protected function getTemplateFolders(string $part = 'template'): array
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
}
