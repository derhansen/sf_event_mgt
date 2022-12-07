<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FluidStandaloneServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function parseStringFluidReturnsExpectedResultForSimpleVariable(): void
    {
        $subject = new FluidStandaloneService();

        $expected = 'This is a subject line with a variable';
        $fluidString = 'This is a subject line with a {variable}';

        $this->assertEquals($expected, $subject->parseStringFluid($fluidString, ['variable' => 'variable']));
    }

    /**
     * @test
     */
    public function parseStringFluidReturnsExpectedResultForExtbaseDomainObjectVariable(): void
    {
        $subject = new FluidStandaloneService();

        $expected = 'Hello Torben Hansen';
        $fluidString = 'Hello {registration.firstname} {registration.lastname}';

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('torben@derhansen.com');

        $this->assertEquals($expected, $subject->parseStringFluid($fluidString, ['registration' => $registration]));
    }
}
