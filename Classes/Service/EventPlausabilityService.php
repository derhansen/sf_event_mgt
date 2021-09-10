<?php

declare(strict_types=1);

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
    private const LANG_FILE = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:';

    /**
     * Enqueues an error flash message, if the event startdate is not before the enddate
     *
     * @param int $startDate
     * @param int $endDate
     */
    public function verifyEventStartAndEnddate(int $startDate, int $endDate): void
    {
        if (!$this->isStartDateBeforeEndDate($startDate, $endDate) && !($startDate === $endDate)) {
            $this->addMessageToFlashMessageQueue(
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.startdateNotBeforeEnddate.message'),
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.startdateNotBeforeEnddate.title'),
                FlashMessage::ERROR
            );
        }
    }

    /**
     * Enqueues an warning flash message, if the event is set to notify the organisator, but no organisator
     * is set or organisator has no email address
     *
     * @param array $databaseRow
     */
    public function verifyOrganisatorConfiguration(array $databaseRow): void
    {
        if ((int)$databaseRow['notify_organisator'] === 0) {
            return;
        }

        if (empty($databaseRow['organisator'])) {
            $this->addMessageToFlashMessageQueue(
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.noOrganisator.message'),
                $this->getLanguageService()->sL(self::LANG_FILE . 'event.noOrganisator.title'),
                FlashMessage::WARNING
            );
            return;
        }

        foreach ($databaseRow['organisator'] as $organisator) {
            if (!GeneralUtility::validEmail($organisator['row']['email'])) {
                $this->addMessageToFlashMessageQueue(
                    $this->getLanguageService()->sL(self::LANG_FILE . 'event.noOrganisatorEmail.message'),
                    $this->getLanguageService()->sL(self::LANG_FILE . 'event.noOrganisatorEmail.title'),
                    FlashMessage::WARNING
                );
            }
        }
    }

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

    protected function addFlashMessage(FlashMessage $flashMessage): void
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
