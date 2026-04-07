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
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Performs plausability checks on current event record in $result and enqueues flash messages if event contains
 * unplausible settings
 */
class EventPlausabilityService
{
    /**
     * Enqueues an error flash message, if the event startdate is not before the enddate
     */
    public function verifyEventStartAndEnddate(?\DateTimeImmutable $startDate = null, ?\DateTimeImmutable $endDate = null): void
    {
        if (!$this->isStartDateBeforeEndDate($startDate, $endDate) && !($startDate === $endDate)) {
            $this->addMessageToFlashMessageQueue(
                (string)$this->getLanguageService()->translate('event.startdateNotBeforeEnddate.message', 'sf_event_mgt.be'),
                (string)$this->getLanguageService()->translate('event.startdateNotBeforeEnddate.title', 'sf_event_mgt.be'),
                ContextualFeedbackSeverity::ERROR
            );
        }
    }

    /**
     * Enqueues an warning flash message, if the event is set to notify the organisator, but no organisator
     * is set or organisator has no email address
     */
    public function verifyOrganisatorConfiguration(array $databaseRow): void
    {
        if ((int)$databaseRow['enable_registration'] === 0 || (int)$databaseRow['notify_organisator'] === 0) {
            return;
        }

        if (empty($databaseRow['organisator'])) {
            $this->addMessageToFlashMessageQueue(
                (string)$this->getLanguageService()->translate('event.noOrganisator.message', 'sf_event_mgt.be'),
                (string)$this->getLanguageService()->translate('event.noOrganisator.title', 'sf_event_mgt.be'),
                ContextualFeedbackSeverity::WARNING
            );
            return;
        }

        foreach ($databaseRow['organisator'] as $organisator) {
            if (!GeneralUtility::validEmail($organisator['row']['email'])) {
                $this->addMessageToFlashMessageQueue(
                    (string)$this->getLanguageService()->translate('event.noOrganisatorEmail.message', 'sf_event_mgt.be'),
                    (string)$this->getLanguageService()->translate('event.noOrganisatorEmail.title', 'sf_event_mgt.be'),
                    ContextualFeedbackSeverity::WARNING
                );
            }
        }
    }

    protected function isStartDateBeforeEndDate(
        ?\DateTimeImmutable $startDate = null,
        ?\DateTimeImmutable $endDate = null
    ): bool {
        if ($startDate === null || $endDate === null) {
            return true;
        }

        return $startDate < $endDate;
    }

    protected function addMessageToFlashMessageQueue(
        string $message,
        string $title = '',
        ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::INFO
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

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
