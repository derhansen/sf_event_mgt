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

/**
 * This event is triggered after a registration has been cancelled
 */
final class WaitlistMoveUpEvent
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var EventController
     */
    private $eventController;

    public function __construct(Event $event, EventController $eventController)
    {
        $this->event = $event;
        $this->eventController = $eventController;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return EventController
     */
    public function getEventController(): EventController
    {
        return $this->eventController;
    }
}
