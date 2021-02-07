<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Event;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * SimultaneousRegistrations ViewHelper
 */
class SimultaneousRegistrationsViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('event', 'object', 'The event', true);
    }

    /**
     * Returns an array with the amount of possible simultaneous registrations
     * respecting the maxRegistrationsPerUser setting and also respects the amount
     * of remaining free places.
     *
     * The returned array index starts at 1 if at least one registration is possible
     *
     * @return array
     */
    public function render()
    {
        /** @var Event $event */
        $event = $this->arguments['event'];
        $minPossibleRegistrations = 1;
        if ($event->getMaxParticipants() > 0 &&
            $event->getMaxRegistrationsPerUser() >= $event->getFreePlaces()
        ) {
            if ($event->getEnableWaitlist() && $event->getFreePlaces() <= 0) {
                $maxPossibleRegistrations = $event->getMaxRegistrationsPerUser();
            } else {
                $maxPossibleRegistrations = $event->getFreePlaces();
            }
        } else {
            $maxPossibleRegistrations = $event->getMaxRegistrationsPerUser();
        }
        $result = [$maxPossibleRegistrations];
        if ($maxPossibleRegistrations >= $minPossibleRegistrations) {
            $arrayWithZeroAsIndex = range($minPossibleRegistrations, $maxPossibleRegistrations);
            $result = array_combine(range(1, count($arrayWithZeroAsIndex)), $arrayWithZeroAsIndex);
        }

        return $result;
    }
}
