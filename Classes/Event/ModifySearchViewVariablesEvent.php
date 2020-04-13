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

/**
 * This event is triggered before the search view is rendered
 */
final class ModifySearchViewVariablesEvent
{
    /**
     * @var array
     */
    private $variables;

    /**
     * @var EventController
     */
    private $eventController;

    public function __construct(array $variables, EventController $eventController)
    {
        $this->variables = $variables;
        $this->eventController = $eventController;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    /**
     * @return EventController
     */
    public function getEventController(): EventController
    {
        return $this->eventController;
    }
}
