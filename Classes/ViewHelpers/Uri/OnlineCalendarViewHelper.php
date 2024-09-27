<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render a link to add an event to a given online calender
 * Currently supports the following online calendar types:
 *
 * - Google Calendar
 * - Outlook Calendar
 * - Office 365 Calendar
 * - Yahoo Calendar
 */
class OnlineCalendarViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('type', 'string', 'The type of online calender', true, 'google');
        $this->registerArgument('event', 'DERHANSEN\SfEventMgt\Domain\Model\Event', 'The event');
    }

    public function render(): string
    {
        /** @var Event $event */
        $event = $this->arguments['event'];
        $type = strtolower($this->arguments['type']);

        // If event has no enddate, set a default enddate (startdate + 1 hour)
        if (!$event->getEnddate()) {
            $enddate = (clone $event->getStartdate())->modify('+1 hour');
            $event->setEnddate($enddate);
        }

        return match ($type) {
            'google' => self::getGoogleCalendarLink($event),
            'outlook' => self::getMicrosoftCalendarLink($event, 'live'),
            'office365' => self::getMicrosoftCalendarLink($event, 'office'),
            'yahoo' => self::getYahooCalendarLink($event),
            default => '',
        };
    }

    private static function getGoogleCalendarLink(Event $event): string
    {
        $baseLink = 'https://www.google.com/calendar/render?';

        $dateFormat = 'Ymd\\THi00\\ZO';
        $arguments = [
            'action' => 'TEMPLATE',
            'text' => $event->getTitle(),
            'dates' => $event->getStartdate()->format($dateFormat) . '/' . $event->getEnddate()->format($dateFormat),
            'details' => strip_tags($event->getDescription()),
        ];

        if ($event->getLocation()) {
            $arguments['location'] = $event->getLocation()->getFullAddress(', ');
        }

        return $baseLink . http_build_query($arguments, '', '&', PHP_QUERY_RFC3986);
    }

    private static function getMicrosoftCalendarLink(Event $event, string $product): string
    {
        $baseLink = 'https://outlook.' . $product . '.com/calendar/0/deeplink/compose?';

        $dateFormat = 'Y-m-d\\TH:i:00O';
        $arguments = [
            'subject' => $event->getTitle(),
            'startdt' => $event->getStartdate()->format($dateFormat),
            'enddt' => $event->getEnddate()->format($dateFormat),
            'body' => strip_tags($event->getDescription()),
            'path' => '/calendar/action/compose&rru=addevent',
        ];

        if ($event->getLocation()) {
            $arguments['location'] = $event->getLocation()->getFullAddress(', ');
        }

        return $baseLink . http_build_query($arguments, '', '&', PHP_QUERY_RFC3986);
    }

    private static function getYahooCalendarLink(Event $event): string
    {
        $baseLink = 'https://calendar.yahoo.com/?';

        $dateFormat = 'Ymd\\THi00\\ZO';
        $arguments = [
            'title' => $event->getTitle(),
            'st' => $event->getStartdate()->format($dateFormat),
            'et' => $event->getEnddate()->format($dateFormat),
            'desc' => strip_tags($event->getDescription()),
            'v' => 60,
        ];

        if ($event->getLocation()) {
            $arguments['in_loc'] = $event->getLocation()->getFullAddress(', ');
        }

        return $baseLink . http_build_query($arguments, '', '&', PHP_QUERY_RFC3986);
    }
}
