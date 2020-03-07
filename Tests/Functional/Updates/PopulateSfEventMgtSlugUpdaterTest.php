<?php
namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Updates\PopulateSfEventMgtSlugUpdater;
use DERHANSEN\SfEventMgt\Updates\RealurlAliasEventSlugUpdater;
use DERHANSEN\SfEventMgt\Utility\MiscUtility;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PopulateSfEventMgtSlugUpdaterTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * @throws \Nimut\TestingFramework\Exception\Exception
     */
    public function setUp()
    {
        parent::setUp();
        $this->importDataSet(__DIR__ . '/../Fixtures/populate_sfeventmgt_slugs.xml');
    }

    /**
     * @test
     */
    public function checkIfWizardIsRequiredReturnsTrue()
    {
        $subject = GeneralUtility::makeInstance(PopulateSfEventMgtSlugUpdater::class);
        $this->assertTrue($subject->checkIfWizardIsRequired());
    }

    /**
     * @test
     */
    public function slugsArePopulated()
    {
        /** @var RealurlAliasEventSlugUpdater $subject */
        $subject = GeneralUtility::makeInstance(PopulateSfEventMgtSlugUpdater::class);

        $subject->executeUpdate();

        // Event UID 1
        $databaseRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_event', 1);
        $this->assertNotEmpty($databaseRecord['slug']);

        // Event UID 2
        $databaseRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_event', 3);
        $this->assertNotEmpty($databaseRecord['slug']);

        // Location UID 1
        $databaseRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_location', 1);
        $this->assertNotEmpty($databaseRecord['slug']);

        // Organisator UID 1
        $databaseRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_organisator', 1);
        $this->assertNotEmpty($databaseRecord['slug']);

        // Speaker UID 1
        $databaseRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_speaker', 1);
        $this->assertNotEmpty($databaseRecord['slug']);

        // After update, checkIfWizardIsRequired must return false
        $this->assertFalse($subject->checkIfWizardIsRequired());
    }
}
