<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class OrganisatorRepositoryTest extends FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository */
    protected $organisatorRepository;

    /** @var array */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     *
     * @throws \TYPO3\CMS\Core\Tests\Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->organisatorRepository = $this->objectManager->get(OrganisatorRepository::class);

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_organisator.xml');
    }

    /**
     * Test if startingpoint is ignored
     *
     * @test
     */
    public function findRecordsByUid()
    {
        $locations = $this->organisatorRepository->findAll();
        self::assertEquals(2, $locations->count());
    }
}
