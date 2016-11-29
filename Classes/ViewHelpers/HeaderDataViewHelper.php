<?php

namespace DERHANSEN\SfEventMgt\ViewHelpers;

/**
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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render data in <head> section of website
 *
 * @author Georg Ringer <typo3@ringerge.org>
 */
class HeaderDataViewHelper extends AbstractViewHelper
{

    /**
     * Renders HeaderData
     *
     * @return void
     */
    public function render()
    {
        $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);
        $pageRenderer->addHeaderData($this->renderChildren());
    }
}
