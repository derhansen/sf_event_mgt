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
use TYPO3\CMS\Extbase\Error\Result;

/**
 * This event is triggered during the validation of a registration
 */
final class RegistrationValidatorEvent
{
    private Registration $registration;
    private Result $result;
    private array $settings;

    public function __construct(Registration $registration, Result $result, array $settings)
    {
        $this->registration = $registration;
        $this->result = $result;
        $this->settings = $settings;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function setResult(Result $result): void
    {
        $this->result = $result;
    }


}
