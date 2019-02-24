<?php
namespace DERHANSEN\SfEventMgt\Updates;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\ConfirmableInterface;
use TYPO3\CMS\Install\Updates\Confirmation;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class ExportService
 */
class RealurlAliasEventSlugUpdater implements UpgradeWizardInterface, ConfirmableInterface
{
    protected $table = 'tx_sfeventmgt_domain_model_event';
    protected $confirmation;

    /**
     * RealurlAliasEventSlugUpdater constructor.
     */
    public function __construct()
    {
        $this->confirmation = new Confirmation(
            'Are you really sure?',
            $this->getDescription(),
            false,
            'Yes, start migration',
            'Skip migration',
            false
        );
    }

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'realurlAliasEventSlugUpdater';
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '[Optional] Migrate realurl alias to slug field of EXT:sf_event_mgt records';
    }

    /**
     * Get description
     *
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'You can migrate EXT:realurl unique alias into event slugs, to ensure that the same alias is used '
                . 'if similar event titles are used. This wizard migrates only matching realurl alias for event '
                . 'entries, where slug field is empty. Requires database table "tx_realurl_uniqalias" from '
                . 'EXT:realurl, but EXT:realurl does not need to be installed. Because only empty event slugs will '
                . 'be filled within this migration, you may decide to empty all event slugs before. The result of '
                . 'this migration can still leave empty slugs fields for event entries. Therfore you should generate '
                . 'these slugs afterwards using the event slug populator update wizard.';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        return $this->checkIfWizardIsRequired();
    }

    /**
     * @return string[] All new fields and tables must exist
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Performs the updates.
     *
     * @return bool Whether everything went smoothly or not
     */
    public function executeUpdate(): bool
    {
        return $this->performRealurlAliasMigration();
    }

    /**
     * Return a confirmation message instance
     *
     * @return \TYPO3\CMS\Install\Updates\Confirmation
     */
    public function getConfirmation(): Confirmation
    {
        return $this->confirmation;
    }

    /**
     * Count valid entries from EXT:realurl table tx_realurl_uniqalias which can be migrated
     * Checks also for existance of third party extension table 'tx_realurl_uniqalias'
     * EXT:realurl requires not to be installed
     *
     * @return bool
     */
    public function checkIfWizardIsRequired(): bool
    {
        $elementCount = 0;
        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilder->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
            // Count valid aliases for events
            $queryBuilder->getRestrictions()->removeAll();
            $elementCount = $queryBuilder->selectLiteral('COUNT(DISTINCT tx_sfeventmgt_domain_model_event.uid)')
                ->from('tx_realurl_uniqalias')
                ->join(
                    'tx_realurl_uniqalias',
                    'tx_sfeventmgt_domain_model_event',
                    'tx_sfeventmgt_domain_model_event',
                    $queryBuilder->expr()->eq(
                        'tx_realurl_uniqalias.value_id',
                        $queryBuilder->quoteIdentifier('tx_sfeventmgt_domain_model_event.uid')
                    )
                )
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_sfeventmgt_domain_model_event.slug',
                                $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_sfeventmgt_domain_model_event.slug')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_sfeventmgt_domain_model_event.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_sfeventmgt_domain_model_event', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], \PDO::PARAM_INT)
                            )
                        )
                    )
                )
                ->execute()->fetchColumn(0);
        }

        return $elementCount > 0;
    }

    /**
     * Perform migration of EXT:realurl unique alias into empty event slugs
     *
     * @return bool
     */
    public function performRealurlAliasMigration(): bool
    {
        $result = true;
        // Check if table 'tx_realurl_uniqalias' exists
        $queryBuilderForRealurl = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_realurl_uniqalias');
        $schemaManager = $queryBuilderForRealurl->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(['tx_realurl_uniqalias']) === true) {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_sfeventmgt_domain_model_event');
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->getRestrictions()->removeAll();

            // Get entries to update
            $statement = $queryBuilder
                ->selectLiteral(
                    'DISTINCT tx_sfeventmgt_domain_model_event.uid, tx_realurl_uniqalias.value_alias, tx_sfeventmgt_domain_model_event.uid'
                )
                ->from('tx_sfeventmgt_domain_model_event')
                ->join(
                    'tx_sfeventmgt_domain_model_event',
                    'tx_realurl_uniqalias',
                    'tx_realurl_uniqalias',
                    $queryBuilder->expr()->eq(
                        'tx_sfeventmgt_domain_model_event.uid',
                        $queryBuilder->quoteIdentifier('tx_realurl_uniqalias.value_id')
                    )
                )
                ->where(
                    $queryBuilder->expr()->andX(
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_sfeventmgt_domain_model_event.slug',
                                $queryBuilder->createNamedParameter('', \PDO::PARAM_STR)
                            ),
                            $queryBuilder->expr()->isNull('tx_sfeventmgt_domain_model_event.slug')
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_sfeventmgt_domain_model_event.sys_language_uid',
                            'tx_realurl_uniqalias.lang'
                        ),
                        $queryBuilder->expr()->eq(
                            'tx_realurl_uniqalias.tablename',
                            $queryBuilder->createNamedParameter('tx_sfeventmgt_domain_model_event', \PDO::PARAM_STR)
                        ),
                        $queryBuilder->expr()->orX(
                            $queryBuilder->expr()->eq(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT)
                            ),
                            $queryBuilder->expr()->gte(
                                'tx_realurl_uniqalias.expire',
                                $queryBuilder->createNamedParameter($GLOBALS['ACCESS_TIME'], \PDO::PARAM_INT)
                            )
                        )
                    )
                )
                ->execute();

            // Update entries
            while ($record = $statement->fetch()) {
                $queryBuilder = $connection->createQueryBuilder();
                $queryBuilder->update('tx_sfeventmgt_domain_model_event')
                    ->where(
                        $queryBuilder->expr()->eq(
                            'uid',
                            $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                        )
                    )
                    ->set('slug', (string)$record['value_alias']);
                $queryBuilder->execute();
            }
        } else {
            $result = false;
        }

        return $result;
    }
}
