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
use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered after a registration has been persisted
 */
final class AfterRegistrationSavedEvent
{
    private Registration $registration;
    private EventController $eventController;

    public function __construct(Registration $registration, EventController $eventController)
    {
        $this->registration = $registration;
        $this->eventController = $eventController;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getEventController(): EventController
    {
        return $this->eventController;
    }
}
