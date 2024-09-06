<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Uri;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Location;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class OnlineCalendarViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function onlineCalendarViewHelperReturnsExpectedResultsDataProvider(): array
    {
        return [
            'google' => [
                'google',
                'https://www.google.com/calendar/render?action=TEMPLATE&amp;text=A%20test%20event&amp;dates=20210101T180000Z%2B0200%2F20210101T200000Z%2B0200&amp;details=A%20description%20for%20the%20event',
            ],
            'outlook' => [
                'outlook',
                'https://outlook.live.com/calendar/0/deeplink/compose?subject=A%20test%20event&amp;startdt=2021-01-01T18%3A00%3A00%2B0200&amp;enddt=2021-01-01T20%3A00%3A00%2B0200&amp;body=A%20description%20for%20the%20event&amp;path=%2Fcalendar%2Faction%2Fcompose%26rru%3Daddevent',
            ],
            'office365' => [
                'office365',
                'https://outlook.office.com/calendar/0/deeplink/compose?subject=A%20test%20event&amp;startdt=2021-01-01T18%3A00%3A00%2B0200&amp;enddt=2021-01-01T20%3A00%3A00%2B0200&amp;body=A%20description%20for%20the%20event&amp;path=%2Fcalendar%2Faction%2Fcompose%26rru%3Daddevent',
            ],
            'yahoo' => [
                'yahoo',
                'https://calendar.yahoo.com/?title=A%20test%20event&amp;st=20210101T180000Z%2B0200&amp;et=20210101T200000Z%2B0200&amp;desc=A%20description%20for%20the%20event&amp;v=60',
            ],
        ];
    }

    #[DataProvider('onlineCalendarViewHelperReturnsExpectedResultsDataProvider')]
    #[Test]
    public function onlineCalendarViewHelperReturnsExpectedResults(string $type, string $expected): void
    {
        $location = new Location();
        $location->setTitle('A location');
        $location->setAddress('Street 123');
        $location->setZip('12345');
        $location->setCity('A City');
        $location->setCountry('A Country');

        $event = new Event();
        $event->setTitle('A test event');
        $event->setDescription('A description for the event');
        $event->setStartdate(new DateTime('01.01.2021 18:00:00 CEST'));
        $event->setEnddate(new DateTime('01.01.2021 20:00:00 CEST'));

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:uri.onlineCalendar type="{type}" event="{event}" />');
        $context->getVariableProvider()->add('event', $event);
        $context->getVariableProvider()->add('type', $type);

        self::assertEquals($expected, (new TemplateView($context))->render());
    }

    #[Test]
    public function defaultEnddateIsSetToEventsWithNoEnddate(): void
    {
        $location = new Location();
        $location->setTitle('A location');
        $location->setAddress('Street 123');
        $location->setZip('12345');
        $location->setCity('A City');
        $location->setCountry('A Country');

        $event = new Event();
        $event->setTitle('A test event');
        $event->setDescription('A description for the event');
        $event->setStartdate(new DateTime('01.01.2021 19:00:00 CEST'));

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:uri.onlineCalendar type="{type}" event="{event}" />');
        $context->getVariableProvider()->add('event', $event);
        $context->getVariableProvider()->add('type', 'google');

        $expected = 'https://www.google.com/calendar/render?action=TEMPLATE&amp;text=A%20test%20event&amp;dates=20210101T190000Z%2B0200%2F20210101T200000Z%2B0200&amp;details=A%20description%20for%20the%20event';
        self::assertEquals($expected, (new TemplateView($context))->render());
    }
}
