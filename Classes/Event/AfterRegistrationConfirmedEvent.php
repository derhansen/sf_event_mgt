<?php
declare(strict_types = 1);
namespace DERHANSEN\SfEventMgt\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered after a registration has been confirmed
 */
final class AfterRegistrationConfirmedEvent
{
    /**
     * @var Registration
     */
    private $registration;

    /**
     * @var EventController
     */
    private $eventController;

    public function __construct(Registration $registration, EventController $eventController)
    {
        $this->registration = $registration;
        $this->eventController = $eventController;
    }

    /**
     * @return Registration
     */
    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    /**
     * @return EventController
     */
    public function getEventController(): EventController
    {
        return $this->eventController;
    }
}
