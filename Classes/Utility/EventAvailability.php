<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Check if an event record is available in a language
 */
class EventAvailability
{
    /**
     * @param int $languageId
     * @param int $eventId
     * @return bool
     */
    public function check(int $languageId, int $eventId): bool
    {
        /** @var Site $site */
        $site = $this->getRequest()->getAttribute('site');
        $allAvailableLanguagesOfSite = $site->getAllLanguages();

        $targetLanguage = $this->getLanguageFromAllLanguages($allAvailableLanguagesOfSite, $languageId);
        if (!$targetLanguage) {
            throw new \UnexpectedValueException('Target language could not be found', 1608059129);
        }
        return $this->mustBeIncluded($eventId, $targetLanguage);
    }

    /**
     * @param int $eventId
     * @param SiteLanguage $language
     * @return bool
     */
    protected function mustBeIncluded(int $eventId, SiteLanguage $language): bool
    {
        if ($language->getFallbackType() === 'strict' &&
            !$this->isEventAvailableInLanguage($eventId, $language->getLanguageId())
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param SiteLanguage[] $allLanguages
     * @param int $languageId
     * @return SiteLanguage|null
     */
    protected function getLanguageFromAllLanguages(array $allLanguages, int $languageId): ?SiteLanguage
    {
        foreach ($allLanguages as $siteLanguage) {
            if ($siteLanguage->getLanguageId() === $languageId) {
                return $siteLanguage;
            }
        }
        return null;
    }

    /**
     * @param int $eventId
     * @param int $language
     * @return bool
     */
    protected function isEventAvailableInLanguage(int $eventId, int $language): bool
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_sfeventmgt_domain_model_event');
        if ($language === 0) {
            $where = [
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, \PDO::PARAM_INT)),
                    $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(-1, \PDO::PARAM_INT))
                ),
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($eventId, \PDO::PARAM_INT)),
            ];
        } else {
            $where = [
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(-1, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($eventId, \PDO::PARAM_INT))
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter($eventId, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, \PDO::PARAM_INT))
                    ),
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($eventId, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('l10n_parent', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)),
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter($language, \PDO::PARAM_INT))
                    )
                ),
            ];
        }

        $eventsFound = $queryBuilder
           ->count('uid')
           ->from('tx_sfeventmgt_domain_model_event')
           ->where(...$where)
           ->execute()
           ->fetchColumn(0);
        $eventIsAvailable = $eventsFound > 0;

        return $eventIsAvailable;
    }

    protected function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
