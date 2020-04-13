<?php
declare(strict_types = 1);
namespace DERHANSEN\SfEventMgt\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * This event is triggered before findDemanded in EventRepository is executed
 */
final class ModifyEventQueryConstraintsEvent
{
    /**
     * @var array
     */
    private $constraints;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var EventDemand
     */
    private $eventDemand;

    /**
     * @var EventRepository
     */
    private $eventRepository;

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

    /**
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @return QueryInterface
     */
    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    /**
     * @return EventDemand
     */
    public function getEventDemand(): EventDemand
    {
        return $this->eventDemand;
    }

    /**
     * @return EventRepository
     */
    public function getEventRepository(): EventRepository
    {
        return $this->eventRepository;
    }

    /**
     * @param array $constraints
     */
    public function setConstraints(array $constraints): void
    {
        $this->constraints = $constraints;
    }
}
