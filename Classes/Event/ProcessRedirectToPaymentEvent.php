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
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered before the user is redirected to the payment process after confirming the registration.
 * Use this event to decide, if the payment redirect should be processed (e.g. if waitlist registrations also must
 * be paid).
 */
final class ProcessRedirectToPaymentEvent
{
    private bool $processRedirect = true;

    public function __construct(
        private readonly Registration $registration,
        private readonly EventController $eventController,
        private readonly ServerRequestInterface $request
    ) {
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

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
