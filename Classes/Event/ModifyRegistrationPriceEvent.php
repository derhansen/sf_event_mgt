<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event should be used to modify the price set to new registrations
 */
final class ModifyRegistrationPriceEvent
{
    public function __construct(
        private float $price,
        private readonly Event $event,
        private readonly Registration $registration,
        private readonly ServerRequestInterface $request
    ) {
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
