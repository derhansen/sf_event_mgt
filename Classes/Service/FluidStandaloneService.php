<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * FluidStandaloneService - Fallbacks for templatePaths for TYPO3 6.2 LTS
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class FluidStandaloneService
{

    /**
     * The configuration manager
     *
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManager
     * @inject
     */
    protected $configurationManager;

    /**
     * Returns the template folders for the given part
     *
     * @param string $part
     * @return array
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     */
    public function getTemplateFolders($part = 'template')
    {
        $extbaseConfig = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );

        if (!empty($extbaseConfig[$part . 'RootPaths'])) {
            $templatePaths = $extbaseConfig[$part . 'RootPaths'];
        }
        if (empty($templatePaths)) {
            $path = $extbaseConfig[$part . 'RootPath'];
            if (!empty($path)) {
                $templatePaths = $path;
            }
        }
        if (empty($templatePaths)) {
            $templatePaths = [];
            $templatePaths[] = 'EXT:sf_event_mgt/Resources/Private/' . ucfirst($part) . 's/';
        }

        $absolutePaths = [];
        foreach ($templatePaths as $templatePath) {
            $absolutePaths[] = GeneralUtility::getFileAbsFileName($templatePath);
        }
        return $absolutePaths;
    }

    /**
     * Return path and filename for a file or path.
     *        Only the first existing file/path will be returned.
     *        respect *RootPaths and *RootPath
     *
     * @param string $pathAndFilename e.g. Email/Name.html
     * @param string $part "template", "partial", "layout"
     * @return string Filename/path
     */
    public function getTemplatePath($pathAndFilename, $part = 'template')
    {
        $matches = $this->getTemplatePaths($pathAndFilename, $part);
        return !empty($matches) ? end($matches) : '';
    }

    /**
     * Return path and filename for one or many files/paths.
     *        Only existing files/paths will be returned.
     *        respect *RootPaths and *RootPath
     *
     * @param string $pathAndFilename Path/filename (Email/Name.html) or path
     * @param string $part "template", "partial", "layout"
     * @return array All existing matches found
     */
    protected function getTemplatePaths($pathAndFilename, $part = 'template')
    {
        $matches = [];
        $absolutePaths = $this->getTemplateFolders($part);
        foreach ($absolutePaths as $absolutePath) {
            if (file_exists($absolutePath . $pathAndFilename)) {
                $matches[] = $absolutePath . $pathAndFilename;
            }
        }
        return $matches;
    }
}