<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * SimultaneousRegistrations ViewHelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SimultaneousRegistrationsViewHelper extends AbstractViewHelper
{
    /**
     * Returns an array with the amount of possible simultaneous registrations
     * respecting the maxRegistrationsPerUser setting and also respects the amount
     * of remaining free places.
     *
     * The returned array index starts at 1 if at least one registration is possible
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Event $event Event
     *
     * @return array
     */
    public function render($event)
    {
        $minPossibleRegistrations = 1;
        if ($event->getMaxParticipants() > 0 &&
            $event->getMaxRegistrationsPerUser() >= $event->getFreePlaces() &&
            !$event->getEnableWaitlist()
        ) {
            $maxPossibleRegistrations = $event->getFreePlaces();
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
