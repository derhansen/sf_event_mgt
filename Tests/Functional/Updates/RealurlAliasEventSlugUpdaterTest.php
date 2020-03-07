<?php
namespace FriendsOfTYPO3\TtAddress\Tests\Functional\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Updates\RealurlAliasEventSlugUpdater;
use DERHANSEN\SfEventMgt\Utility\MiscUtility;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RealurlAliasEventSlugUpdaterTest extends FunctionalTestCase
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
        $this->createRealUrlUniqueAliasTable();
        $this->importDataSet(__DIR__ . '/../Fixtures/realurl_slug_update.xml');
    }

    /**
     * @test
     */
    public function checkIfWizardIsRequiredReturnsTrue()
    {
        $subject = GeneralUtility::makeInstance(RealurlAliasEventSlugUpdater::class);
        $this->assertTrue($subject->checkIfWizardIsRequired());
    }

    /**
     * @test
     */
    public function updateWorks()
    {
        /** @var RealurlAliasEventSlugUpdater $subject */
        $subject = GeneralUtility::makeInstance(RealurlAliasEventSlugUpdater::class);

        $subject->performRealurlAliasMigration();

        // For event uid 1, the slug must be set
        $eventRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_event', 1);
        $this->assertEquals('event-1-slug-from-realurl', $eventRecord['slug']);

        // For event uid 2, the slug must not be set (alias expired)
        $eventRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_event', 2);
        $this->assertEquals('', $eventRecord['slug']);

        // For event uid 3 with sys_language_uid 3, the slug must be set
        $eventRecord = BackendUtility::getRecord('tx_sfeventmgt_domain_model_event', 3);
        $this->assertEquals('event-3-slug-from-realurl', $eventRecord['slug']);

        // After update, checkIfWizardIsRequired must return false
        $this->assertFalse($subject->checkIfWizardIsRequired());
    }

    /**
     * Creates the tx_realurl_uniqalias table.
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createRealUrlUniqueAliasTable()
    {
        $con = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionByName('Default');
        $con->query('DROP TABLE IF EXISTS `tx_realurl_uniqalias`;');
        $con->query('
            CREATE TABLE `tx_realurl_uniqalias` (
              `uid` int(11) NOT NULL AUTO_INCREMENT,
              `pid` int(11) NOT NULL DEFAULT \'0\',
              `tablename` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\',
              `field_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\',
              `field_id` varchar(60) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\',
              `value_alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT \'\',
              `value_id` int(11) NOT NULL DEFAULT \'0\',
              `lang` int(11) NOT NULL DEFAULT \'0\',
              `expire` int(11) NOT NULL DEFAULT \'0\',
              PRIMARY KEY (`uid`),
              KEY `parent` (`pid`),
              KEY `tablename` (`tablename`),
              KEY `bk_realurl01` (`field_alias`(20),`field_id`,`value_id`,`lang`,`expire`),
              KEY `bk_realurl02` (`tablename`(32),`field_alias`(20),`field_id`,`value_alias`(20),`expire`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ');
    }
}
