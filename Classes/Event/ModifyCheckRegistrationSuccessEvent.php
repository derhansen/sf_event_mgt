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
 * This event is triggered in registrationService after all `checkRegistrationSuccess` have been processed.
 * Event listeners can use this event to modify the `$success` and `$result` variable (e.g. if custom logic
 * is implemented)
 */
final class ModifyCheckRegistrationSuccessEvent
{
    public function __construct(private bool $success, private int $result, private readonly Registration $registration)
    {
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getResult(): int
    {
        return $this->result;
    }

    public function setResult(int $result): void
    {
        $this->result = $result;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }
}
