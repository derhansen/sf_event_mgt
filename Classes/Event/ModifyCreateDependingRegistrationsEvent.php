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
 * This event is triggered before depending registrations are created. Use setCreateDependingRegistrations to
 * override the original bahavior
 */
final class ModifyCreateDependingRegistrationsEvent
{
    public function __construct(
        private readonly Registration $registration,
        private bool $createDependingRegistrations,
        private readonly EventController $eventController
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

    public function getCreateDependingRegistrations(): bool
    {
        return $this->createDependingRegistrations;
    }

    public function setCreateDependingRegistrations(bool $createDependingRegistrations): void
    {
        $this->createDependingRegistrations = $createDependingRegistrations;
    }
}
