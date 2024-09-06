<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class SimultaneousRegistrationsViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function simultaneousRegistrationsDataProvider(): array
    {
        return [
            'maxRegistrationsAndFreePlacesEqual' => [
                5,
                1,
                1,
                false,
                [
                    1 => 1,
                ],
            ],
            'moreMaxRegistrationsThanFreePlaces' => [
                5,
                1,
                2,
                false,
                [
                    1 => 1,
                ],
            ],
            'moreFreePlacesThanMaxRegistrations' => [
                5,
                10,
                1,
                false,
                [
                    1 => 1,
                ],
            ],
            'moreFreePlacesThanMaxRegistrationsWithSimultaneousAllowed' => [
                5,
                10,
                5,
                false,
                [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                ],
            ],
            'noFreePlacesAvailable' => [
                5,
                0,
                1,
                false,
                [
                    0 => 0,
                ],
            ],
            'noFreePlacesAndNoMaxRegistrations' => [
                5,
                0,
                0,
                false,
                [
                    0 => 0,
                ],
            ],
            'noMaxParticipants' => [
                0,
                0,
                3,
                false,
                [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                ],
            ],
            'SimultaneousAllowedWithWaitlistAndNotEnoughFreePlacesForFullRegistration' => [
                5,
                1,
                2,
                true,
                [
                    1 => 1, // Must only show one possible registration (which will not be on the waitlist)
                ],
            ],
            'SimultaneousAllowedWithWaitlistAndNoFreePlacesforFullRegistration' => [
                5,
                0,
                2,
                true,
                [
                    1 => 1,
                    2 => 2,
                ],
            ],
        ];
    }

    #[DataProvider('simultaneousRegistrationsDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValues(
        int $maxParticipants,
        int $freePlaces,
        int $maxRegistrations,
        bool $waitlist,
        array $expected
    ): void {
        $mockEvent = $this->getMockBuilder(Event::class)->getMock();
        $mockEvent->expects(self::any())->method('getFreePlaces')->willReturn($freePlaces);
        $mockEvent->expects(self::any())->method('getMaxParticipants')->willReturn($maxParticipants);
        $mockEvent->expects(self::any())->method('getEnableWaitlist')->willReturn($waitlist);
        $mockEvent->expects(self::any())->method('getMaxRegistrationsPerUser')->willReturn($maxRegistrations);

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:event.simultaneousRegistrations event="{event}" />');
        $context->getVariableProvider()->add('event', $mockEvent);
        self::assertEquals($expected, (new TemplateView($context))->render());
    }
}
