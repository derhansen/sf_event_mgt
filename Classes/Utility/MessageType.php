<?php
namespace DERHANSEN\SfEventMgt\Utility;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * MessageType
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class MessageType
{
    const REGISTRATION_NEW = 0;
    const REGISTRATION_CONFIRMED = 1;
    const CUSTOM_NOTIFICATION = 2;
    const REGISTRATION_CANCELLED = 3;
    const REGISTRATION_WAITLIST_NEW = 4;
    const REGISTRATION_WAITLIST_CONFIRMED = 5;
}
