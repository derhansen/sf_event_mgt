<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class CacheService
 */
class EventCacheService
{
    /**
     * Adds cache tags to page cache by event records.
     *
     * Following cache tags will be added to tsfe:
     * "tx_sfeventmgt_uid_[event:uid]"
     */
    public function addCacheTagsByEventRecords(array $eventRecords): void
    {
        $cacheTags = [];
        foreach ($eventRecords as $event) {
            // cache tag for each event record
            $cacheTags[] = 'tx_sfeventmgt_uid_' . $event->getUid();
        }
        if (count($cacheTags) > 0) {
            $this->getTypoScriptFrontendController()->addCacheTags($cacheTags);
        }
    }

    /**
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_sfeventmgt_pid_[event:pid]
     */
    public function addPageCacheTagsByEventDemandObject(EventDemand $demand): void
    {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageId) {
                $cacheTags[] = 'tx_sfeventmgt_pid_' . $pageId;
            }
        }
        if (count($cacheTags) > 0) {
            $this->getTypoScriptFrontendController()->addCacheTags($cacheTags);
        }
    }

    /**
     * Flushes the page cache by event tags for the given event uid and pid
     */
    public function flushEventCache(int $eventUid = 0, int $eventPid = 0): void
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheTagsToFlush = [];

        if ($eventUid > 0) {
            $cacheTagsToFlush[] = 'tx_sfeventmgt_uid_' . $eventUid;
        }
        if ($eventPid > 0) {
            $cacheTagsToFlush[] = 'tx_sfeventmgt_pid_' . $eventPid;
        }

        foreach ($cacheTagsToFlush as $cacheTagToFlush) {
            $cacheManager->flushCachesInGroupByTag('pages', $cacheTagToFlush);
        }
    }

    protected function getTypoScriptFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'] ?? null;
    }
}
