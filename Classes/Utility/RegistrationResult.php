<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

/**
 * Constants used for the registration result
 */
class RegistrationResult
{
    public const REGISTRATION_SUCCESSFUL = 0;
    public const REGISTRATION_FAILED_EVENT_EXPIRED = 1;
    public const REGISTRATION_FAILED_MAX_PARTICIPANTS = 2;
    public const REGISTRATION_NOT_ENABLED = 3;
    public const REGISTRATION_FAILED_DEADLINE_EXPIRED = 4;
    public const REGISTRATION_FAILED_NOT_ENOUGH_FREE_PLACES = 5;
    public const REGISTRATION_FAILED_MAX_AMOUNT_REGISTRATIONS_EXCEEDED = 6;
    public const REGISTRATION_FAILED_EMAIL_NOT_UNIQUE = 7;
    public const REGISTRATION_SUCCESSFUL_WAITLIST = 8;
}
