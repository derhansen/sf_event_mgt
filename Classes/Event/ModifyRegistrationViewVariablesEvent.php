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

/**
 * This event is triggered before the registration view is rendered
 */
final class ModifyRegistrationViewVariablesEvent
{
    public function __construct(private array $variables, private readonly EventController $eventController)
    {
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    public function getEventController(): EventController
    {
        return $this->eventController;
    }
}
