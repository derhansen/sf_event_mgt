<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class CacheService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class EventCacheService
{
    /**
     * Adds cache tags to page cache by event records.
     *
     * Following cache tags will be added to tsfe:
     * "tx_sfeventmgt_uid_[event:uid]"
     *
     * @param array $eventRecords array with event records
     * @return void
     */
    public function addCacheTagsByEventRecords(array $eventRecords)
    {
        $cacheTags = [];
        foreach ($eventRecords as $event) {
            // cache tag for each event record
            $cacheTags[] = 'tx_sfeventmgt_uid_' . $event->getUid();
        }
        if (count($cacheTags) > 0) {
            self::getTypoScriptFrontendController()->addCacheTags($cacheTags);
        }
    }

    /**
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_sfeventmgt_pid_[event:pid]
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand
     * @return void
     */
    public function addPageCacheTagsByEventDemandObject(\DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand $demand)
    {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageId) {
                $cacheTags[] = 'tx_sfeventmgt_pid_' . $pageId;
            }
        }
        if (count($cacheTags) > 0) {
            self::getTypoScriptFrontendController()->addCacheTags($cacheTags);
        }
    }

    public function flushEventCache(int $eventUid = 0, int $eventPid = 0)
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

    /**
     * @return \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
     */
    protected function getTypoScriptFrontendController()
    {
        return $GLOBALS['TSFE'] ?: null;
    }
}
