<?php
namespace DERHANSEN\SfEventMgt\Updates;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Class PopulateSfEventMgtSlugUpdater
 */
class PopulateSfEventMgtSlugUpdater implements UpgradeWizardInterface
{
    protected $tables = [
        'tx_sfeventmgt_domain_model_event',
        'tx_sfeventmgt_domain_model_location',
        'tx_sfeventmgt_domain_model_organisator',
        'tx_sfeventmgt_domain_model_speaker',
    ];

    /**
     * @return string Unique identifier of this updater
     */
    public function getIdentifier(): string
    {
        return 'populateSfEventMgtSlugUpdater';
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'Introduce URL parts ("slugs") to all records of EXT:sf_event_mgt';
    }

    /**
     * Get description
     *
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'This wizard will fill URL parts ("slugs") for events, locations, organisators and speakers.';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (true) or not (false)
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
        foreach ($this->tables as $table) {
            $this->populateSlugs($table);
        }

        return true;
    }

    /**
     * Returns, if the wizard must be executed
     *
     * @return bool
     */
    public function checkIfWizardIsRequired(): bool
    {
        $numberOfEntries = 0;
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach ($this->tables as $table) {
            $queryBuilder = $connectionPool->getQueryBuilderForTable($table);
            $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

            $numberOfEntries += $queryBuilder
                ->count('uid')
                ->from($table)
                ->where(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter('')),
                        $queryBuilder->expr()->isNull('slug')
                    )
                )
                ->execute()
                ->fetchColumn();
        }

        return $numberOfEntries > 0;
    }

    /**
     * Fills the given database table with slugs based on the configuration of the field.
     *
     * @param string $table
     * @return void
     */
    public function populateSlugs(string $table)
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('slug', $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull('slug')
                )
            )
            // Ensure that live workspace records are handled first
            ->addOrderBy('t3ver_wsid', 'asc')
            // Ensure that all pages are run through "per parent page" field, and in the correct sorting values
            ->addOrderBy('pid', 'asc')
            ->addOrderBy('sorting', 'asc')
            ->execute();

        $fieldConfig = $GLOBALS['TCA'][$table]['columns']['slug']['config'];
        $evalInfo = !empty($fieldConfig['eval']) ? GeneralUtility::trimExplode(',', $fieldConfig['eval'], true) : [];
        $hasToBeUniqueInSite = in_array('uniqueInSite', $evalInfo, true);
        $hasToBeUniqueInPid = in_array('uniqueInPid', $evalInfo, true);
        $slugHelper = GeneralUtility::makeInstance(SlugHelper::class, $table, 'slug', $fieldConfig);
        while ($record = $statement->fetch()) {
            $recordId = (int)$record['uid'];
            $pid = (int)$record['pid'];
            $slug = $slugHelper->generate($record, $pid);

            $state = RecordStateFactory::forName($table)
                ->fromArray($record, $pid, $recordId);
            if ($hasToBeUniqueInSite && !$slugHelper->isUniqueInSite($slug, $state)) {
                $slug = $slugHelper->buildSlugForUniqueInSite($slug, $state);
            }
            if ($hasToBeUniqueInPid && !$slugHelper->isUniqueInPid($slug, $state)) {
                $slug = $slugHelper->buildSlugForUniqueInPid($slug, $state);
            }

            $connection->update(
                $table,
                ['slug' => $slug],
                ['uid' => $recordId]
            );
        }
    }
}
