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
use DERHANSEN\SfEventMgt\Service\RegistrationService;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered after a registration moved from the waitlist
 */
final readonly class AfterRegistrationMovedFromWaitlist
{
    public function __construct(
        private Registration $registration,
        private RegistrationService $registrationService,
        private ServerRequestInterface $request
    ) {
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getRegistrationService(): RegistrationService
    {
        return $this->registrationService;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
