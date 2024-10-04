<?php

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

namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render a link to a page using the current core routing interface. Should be used in emails
 * sent via the notification backend module in order to create frontend links in email content.
 */
final class PageViewHelper extends AbstractViewHelper
{
    public function __construct(protected readonly SiteFinder $siteFinder)
    {
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('pageUid', 'int', 'Target PID');
        $this->registerArgument('additionalParams', 'array', 'Query parameters to be attached to the resulting URI', false, []);
    }

    public function render(): string
    {
        $pageUid = (int)$this->arguments['pageUid'];
        $additionalParams = $this->arguments['additionalParams'];
        $site = $this->siteFinder->getSiteByPageId($pageUid);
        return (string)($site->getRouter()->generateUri((string)$pageUid, $additionalParams));
    }
}
