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
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FluidStandaloneServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function parseStringFluidReturnsExpectedResultForSimpleVariable(): void
    {
        $subject = new FluidStandaloneService();

        $expected = 'This is a subject line with a variable';
        $fluidString = 'This is a subject line with a {variable}';

        self::assertEquals(
            $expected,
            $subject->parseStringFluid($this->getExtbaseRequest(), $fluidString, ['variable' => 'variable'])
        );
    }

    #[Test]
    public function parseStringFluidReturnsExpectedResultForExtbaseDomainObjectVariable(): void
    {
        $subject = new FluidStandaloneService();

        $expected = 'Hello Torben Hansen';
        $fluidString = 'Hello {registration.firstname} {registration.lastname}';

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('torben@derhansen.com');

        self::assertEquals(
            $expected,
            $subject->parseStringFluid($this->getExtbaseRequest(), $fluidString, ['registration' => $registration])
        );
    }

    protected function getExtbaseRequest(): RequestInterface
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        return new Request($serverRequest);
    }
}
