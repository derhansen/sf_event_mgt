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
 * Disable language item on a detail page if the event is not translated
 *
 * 20 = DERHANSEN\SfEventMgt\DataProcessing\DisableLanguageMenuProcessor
 * 20.menus = languageMenu
 */
class DisableLanguageMenuProcessor implements DataProcessorInterface
{
    /**
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     * @return array
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        if (!$processorConfiguration['menus']) {
            return $processedData;
        }

        $eventId = $this->getEventId();
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

    /**
     * @param int $eventId
     * @param array $menu
     */
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

    /**
     * @return int
     */
    protected function getEventId(): int
    {
        $eventId = 0;
        /** @var PageArguments $pageArguments */
        $pageArguments = $this->getRequest()->getAttribute('routing');
        if (isset($pageArguments->getRouteArguments()['tx_sfeventmgt_pievent']['event'])) {
            $eventId = (int)$pageArguments->getRouteArguments()['tx_sfeventmgt_pievent']['event'];
        } elseif (isset($this->getRequest()->getQueryParams()['tx_sfeventmgt_pievent']['event'])) {
            $eventId = (int)$this->getRequest()->getQueryParams()['tx_sfeventmgt_pievent']['event'];
        }

        return $eventId;
    }

    /**
     * @return ServerRequestInterface
     */
    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
