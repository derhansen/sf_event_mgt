<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Form\FormDataProvider;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HideInlineRegistrations implements FormDataProviderInterface
{
    public function addData(array $result): array
    {
        if ($result['tableName'] !== 'tx_sfeventmgt_domain_model_event' || !is_int($result['databaseRow']['uid'])) {
            return $result;
        }

        $extConfig = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('sf_event_mgt');
        if ((bool)$extConfig['hideInlineRegistrations'] === false) {
            return $result;
        }

        $amountOfRegistrations = $this->getRegistrationCount($result['databaseRow']['uid']);

        if ($amountOfRegistrations > (int)$extConfig['hideInlineRegistrationsLimit']) {
            $message = sprintf(
                $this->getLanguageService()->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:backend.hideInlineRegistrations.description'),
                (string)$amountOfRegistrations
            );

            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                $message,
                $this->getLanguageService()->sL('LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:backend.hideInlineRegistrations.title'),
                FlashMessage::INFO,
                true
            );

            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
            // @extensionScannerIgnoreLine
            $messageQueue->addMessage($flashMessage);

            // Unset the field "registration" and "registration_waitlist", so no data will be shows/loaded
            unset($result['processedTca']['columns']['registration']);
            unset($result['processedTca']['columns']['registration_waitlist']);
        }

        return $result;
    }

    protected function getRegistrationCount(int $eventId): int
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_sfeventmgt_domain_model_registration');
        $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);

        return (int)$queryBuilder->count('uid')
            ->from('tx_sfeventmgt_domain_model_registration')
            ->where(
                $queryBuilder->expr()->eq(
                    'event',
                    $queryBuilder->createNamedParameter($eventId, Connection::PARAM_INT)
                )
            )
            ->execute()
            ->fetchColumn();
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
