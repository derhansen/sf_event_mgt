<?php
namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class LocationRepositoryTest extends FunctionalTestCase
{
    /** @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface The object manager */
    protected $objectManager;

    /** @var \DERHANSEN\SfEventMgt\Domain\Repository\LocationRepository */
    protected $locationRepository;

    /** @var array */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     *
     * @throws \TYPO3\CMS\Core\Tests\Exception
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->objectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        $this->locationRepository = $this->objectManager->get('DERHANSEN\\SfEventMgt\\Domain\\Repository\\LocationRepository');

        $this->importDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_location.xml');
    }

    /**
     * Test if startingpoint is ignored
     *
     * @test
     * @return void
     */
    public function findRecordsByUid()
    {
        $locations = $this->locationRepository->findAll();

        $this->assertEquals(2, $locations->count());
    }
}
