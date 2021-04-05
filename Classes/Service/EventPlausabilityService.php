<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Performs plausability checks on current event record in $result and enqueues flash messages if event contains
 * unplausible settings
 */
class EventPlausabilityService
{
    const LANG_FILE = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Enqueues an error flash message, if the event startdate is not before the enddate
     *
     * @param int $startDate
     * @param int $endDate
     */
    public function verifyEventStartAndEnddate(int $startDate, int $endDate): void
    {
        if (!$this->isStartDateBeforeEndDate($startDate, $endDate)) {
            $this->addMessageToFlashMessageQueue(
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.startdateNotBeforeEnddate.message'),
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.startdateNotBeforeEnddate.title'),
                FlashMessage::ERROR
            );
        }
    }

    /**
     * Returns if the startdate is before the enddate
     *
     * @param int $startDate
     * @param int $endDate
     * @return bool
     */
    protected function isStartDateBeforeEndDate(int $startDate, int $endDate): bool
    {
        if ($startDate === 0 || $endDate === 0) {
            return true;
        }

        return $startDate < $endDate;
    }

    protected function addMessageToFlashMessageQueue(
        string $message,
        string $title = '',
        $severity = FlashMessage::INFO
    ): void {
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            $title,
            $severity,
            true
        );

        $this->addFlashMessage($flashMessage);
    }

    protected function addFlashMessage(FlashMessage $flashMessage)
    {
        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $defaultFlashMessageQueue->enqueue($flashMessage);
    }

    protected function getLanguageService(): ?LanguageService
    {
        return $GLOBALS['LANG'] ?? null;
    }
}
