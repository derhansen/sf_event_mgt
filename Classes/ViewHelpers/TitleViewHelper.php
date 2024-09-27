<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers;

use DERHANSEN\SfEventMgt\PageTitle\EventPageTitleProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render the page title and indexed search title
 */
class TitleViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('pageTitle', 'String', 'The page title');
    }

    public function render(): void
    {
        $pageTitle = $this->arguments['pageTitle'] ?? '';
        if ($pageTitle !== '') {
            GeneralUtility::makeInstance(EventPageTitleProvider::class)->setTitle($pageTitle);
        }
    }
}
