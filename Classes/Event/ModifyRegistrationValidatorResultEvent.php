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
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Extbase\Error\Result;

/**
 * This event should be used to modify the result of the RegistrationValidator. As an example, additional validation
 * tasks on registration object can be performed and potential validation failures can be added to the result.
 *
 * Example usage: $event->getResult()->forProperty('zip')->addError(new Error('Error for ZIP field', 1726287471));
 */
final readonly class ModifyRegistrationValidatorResultEvent
{
    public function __construct(
        private Registration $registration,
        private array $settings,
        private Result $result,
        private ServerRequestInterface $request
    ) {
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
