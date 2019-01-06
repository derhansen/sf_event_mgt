<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

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
