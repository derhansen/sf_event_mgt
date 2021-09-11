<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * This event is triggered before findDemanded in EventRepository is executed
 */
final class ModifyEventQueryConstraintsEvent
{
    private array $constraints;
    private QueryInterface $query;
    private EventDemand $eventDemand;
    private EventRepository $eventRepository;

    public function __construct(
        array $constraints,
        QueryInterface $query,
        EventDemand $eventDemand,
        EventRepository $eventRepository
    ) {
        $this->constraints = $constraints;
        $this->query = $query;
        $this->eventDemand = $eventDemand;
        $this->eventRepository = $eventRepository;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function getEventDemand(): EventDemand
    {
        return $this->eventDemand;
    }

    public function getEventRepository(): EventRepository
    {
        return $this->eventRepository;
    }

    public function setConstraints(array $constraints): void
    {
        $this->constraints = $constraints;
    }
}
