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
 * This event is triggered before the user is redirected to the payment process after confirming the registration.
 * Use this event to decide, if the payment redirect should be processed (e.g. if waitlist registrations also must
 * be paid).
 */
final class ProcessRedirectToPaymentEvent
{
    private bool $processRedirect = true;
    private Registration $registration;
    private EventController $eventController;

    public function __construct(
        Registration $registration,
        EventController $eventController
    ) {
        $this->registration = $registration;
        $this->eventController = $eventController;
    }

    public function getProcessRedirect(): bool
    {
        return $this->processRedirect;
    }

    public function setProcessRedirect(bool $processRedirect): void
    {
        $this->processRedirect = $processRedirect;
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
