<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered before the depending registrations are cancelled. Event listeners can use this
 * event to stop cancellation of depending registrations by setting `$processCancellation` to false
 */
final class ProcessCancelDependingRegistrationsEvent
{
    public function __construct(
        private readonly Registration $registration,
        private bool $processCancellation
    ) {
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getProcessCancellation(): bool
    {
        return $this->processCancellation;
    }

    public function setProcessCancellation(bool $processCancellation): void
    {
        $this->processCancellation = $processCancellation;
    }
}
