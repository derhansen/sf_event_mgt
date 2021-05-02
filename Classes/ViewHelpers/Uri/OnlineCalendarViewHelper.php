<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Uri;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

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
    use CompileWithRenderStatic;

    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('type', 'string', 'The type of online calender', true, 'google');
        $this->registerArgument('event', 'DERHANSEN\SfEventMgt\Domain\Model\Event', 'The event');
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        /** @var Event $event */
        $event = $arguments['event'];
        $type = strtolower($arguments['type']);

        switch ($type) {
            case 'google':
                $link = self::getGoogleCalendarLink($event);
                break;
            case 'outlook':
                $link = self::getMicrosoftCalendarLink($event, 'live');
                break;
            case 'office365':
                $link = self::getMicrosoftCalendarLink($event, 'office');
                break;
            case 'yahoo':
                $link = self::getYahooCalendarLink($event);
                break;
            default:
                $link = '';
        }

        return $link;
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
