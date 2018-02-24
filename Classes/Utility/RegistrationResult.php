<?php
namespace DERHANSEN\SfEventMgt\Utility;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * RegistrationResult
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationResult
{
    const REGISTRATION_SUCCESSFUL = 0;
    const REGISTRATION_FAILED_EVENT_EXPIRED = 1;
    const REGISTRATION_FAILED_MAX_PARTICIPANTS = 2;
    const REGISTRATION_NOT_ENABLED = 3;
    const REGISTRATION_FAILED_DEADLINE_EXPIRED = 4;
    const REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES = 5;
    const REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED = 6;
    const REGISTRATION_FAILED_EMAIL_NOT_UNIQUE = 7;
    const REGISTRATION_SUCCESSFUL_WAITLIST = 8;
}