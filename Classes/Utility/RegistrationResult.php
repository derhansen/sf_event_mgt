<?php
namespace DERHANSEN\SfEventMgt\Utility;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
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