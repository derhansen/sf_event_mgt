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
 * This event is triggered after a registration has been cancelled
 */
final readonly class AfterRegistrationCancelledEvent
{
    public function __construct(
        private Registration $registration,
        private EventController $eventController,
        private ServerRequestInterface $request
    ) {
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
