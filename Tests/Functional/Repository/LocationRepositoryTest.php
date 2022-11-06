<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository
 */
class LocationRepositoryTest extends FunctionalTestCase
{
    protected LocationRepository $locationRepository;

    /** @var array */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->locationRepository = GeneralUtility::makeInstance(LocationRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_location.csv');
    }

    /**
     * @test
     */
    public function startingPageIsIgnored(): void
    {
        $locations = $this->locationRepository->findAll();

        self::assertEquals(2, $locations->count());
    }
}
