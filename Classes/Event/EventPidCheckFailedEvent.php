<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered when the event PID check failed
 */
final readonly class EventPidCheckFailedEvent
{
    public function __construct(
        private Event $event,
        private EventController $eventController,
        private ServerRequestInterface $request
    ) {
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getEventController(): EventController
    {
        return $this->eventController;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
