<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

class ICalendarService
{
    public function __construct(protected readonly FluidStandaloneService $fluidStandaloneService)
    {
    }

    /**
     * Initiates the ICS download for the given event
     */
    public function downloadiCalendarFile(RequestInterface $request, Event $event): void
    {
        $content = $this->getICalendarContent($request, $event);
        header('Content-Disposition: attachment; filename="event' . $event->getUid() . '.ics"');
        header('Content-Type: text/calendar');
        header('Content-Length: ' . strlen($content));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: no-cache');
        echo $content;
    }

    /**
     * Returns the rendered iCalendar entry for the given event
     * according to RFC 2445
     */
    public function getiCalendarContent(RequestInterface $request, Event $event): string
    {
        $variables = [
            'event' => $event,
            'typo3Host' => GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'),
        ];

        $icalContent = $this->fluidStandaloneService->renderTemplate(
            $request,
            'Event/ICalendar.txt',
            $variables,
            'SfEventMgt',
            'Pieventdetail',
            'txt'
        );

        // Remove empty lines
        $icalContent = preg_replace('/^\h*\v+/m', '', $icalContent);
        // Finally replace new lines with CRLF
        return str_replace(chr(10), chr(13) . chr(10), $icalContent);
    }
}
