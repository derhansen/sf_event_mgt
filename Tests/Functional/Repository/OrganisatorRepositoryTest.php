<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Repository\OrganisatorRepository;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class OrganisatorRepositoryTest extends FunctionalTestCase
{
    protected OrganisatorRepository $organisatorRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public function setUp(): void
    {
        parent::setUp();
        $this->organisatorRepository = $this->getContainer()->get(OrganisatorRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/tx_sfeventmgt_domain_model_organisator.csv');

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    /**
     * @test
     */
    public function startingPageIsIgnored(): void
    {
        $locations = $this->organisatorRepository->findAll();
        self::assertEquals(2, $locations->count());
    }
}
