<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Evaluation;

use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TimeRestrictionEvaluator
{
    /**
     * Checks if $value can be interpreted with strtotime()
     */
    public function evaluateFieldValue(string $value, string $is_in, bool &$set): string
    {
        $timestamp = strtotime($value);
        $set = empty($value) || $timestamp !== false;

        if (!empty($value) && $timestamp !== false) {
            $languageService = $this->getLanguageService();

            if ($set) {
                $severity = ContextualFeedbackSeverity::INFO;
                $message = sprintf(
                    $languageService->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:evaluation.timeRestriction.info'),
                    $value,
                    date($languageService->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:evaluation.timeRestriction.format'), $timestamp)
                );
            } else {
                $severity = ContextualFeedbackSeverity::ERROR;
                $message = sprintf(
                    $languageService->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:evaluation.timeRestriction.error'),
                    $value
                );
            }

            /** @var FlashMessage $message */
            $message = GeneralUtility::makeInstance(
                FlashMessage::class,
                $message,
                $languageService->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:evaluation.timeRestriction.header'),
                $severity,
                false
            );

            /** @var FlashMessageService $flashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
        }

        return $value;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
