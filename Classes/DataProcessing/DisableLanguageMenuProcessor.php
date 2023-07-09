<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\DataProcessing;

use DERHANSEN\SfEventMgt\Utility\EventAvailability;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Disable language item on detail or registration page, if the event is not translated
 *
 * 20 = DERHANSEN\SfEventMgt\DataProcessing\DisableLanguageMenuProcessor
 * 20.menus = languageMenu
 */
class DisableLanguageMenuProcessor implements DataProcessorInterface
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        if (!$processorConfiguration['menus']) {
            return $processedData;
        }

        $eventId = $this->getEventId($cObj->getRequest());
        if ($eventId === 0) {
            return $processedData;
        }

        $menus = GeneralUtility::trimExplode(',', $processorConfiguration['menus'], true);
        foreach ($menus as $menu) {
            if (isset($processedData[$menu])) {
                $this->handleMenu($eventId, $processedData[$menu]);
            }
        }

        return $processedData;
    }

    protected function handleMenu(int $eventId, array &$menu): void
    {
        $eventAvailability = GeneralUtility::makeInstance(EventAvailability::class);
        foreach ($menu as &$item) {
            if (!$item['available']) {
                continue;
            }
            try {
                $availability = $eventAvailability->check((int)$item['languageId'], $eventId);
                if (!$availability) {
                    $item['available'] = false;
                    $item['availableReason'] = 'sf_event_mgt';
                }
            } catch (\Exception $e) {
            }
        }
    }

    protected function getEventId(ServerRequestInterface$request): int
    {
        $eventId = 0;
        /** @var PageArguments $pageArguments */
        $pageArguments = $request->getAttribute('routing');
        if (isset($pageArguments->getRouteArguments()['tx_sfeventmgt_pieventdetail']['event'])) {
            $eventId = (int)$pageArguments->getRouteArguments()['tx_sfeventmgt_pieventdetail']['event'];
        } elseif (isset($request->getQueryParams()['tx_sfeventmgt_pieventdetail']['event'])) {
            $eventId = (int)$request->getQueryParams()['tx_sfeventmgt_pieventdetail']['event'];
        } elseif (isset($pageArguments->getRouteArguments()['tx_sfeventmgt_pieventregistration']['event'])) {
            $eventId = (int)$pageArguments->getRouteArguments()['tx_sfeventmgt_pieventregistration']['event'];
        } elseif (isset($request->getQueryParams()['tx_sfeventmgt_pieventregistration']['event'])) {
            $eventId = (int)$request->getQueryParams()['tx_sfeventmgt_pieventregistration']['event'];
        }

        return $eventId;
    }
}
