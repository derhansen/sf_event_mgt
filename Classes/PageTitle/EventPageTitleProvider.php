<?php
namespace DERHANSEN\SfEventMgt\PageTitle;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

/**
 * Class EventPageTitleProvider
 */
class EventPageTitleProvider extends AbstractPageTitleProvider
{
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
