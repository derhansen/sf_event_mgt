<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

/**
 * Constants used for notifications
 */
class MessageType
{
    public const REGISTRATION_NEW = 0;
    public const REGISTRATION_CONFIRMED = 1;
    public const CUSTOM_NOTIFICATION = 2;
    public const REGISTRATION_CANCELLED = 3;
    public const REGISTRATION_WAITLIST_NEW = 4;
    public const REGISTRATION_WAITLIST_CONFIRMED = 5;
    public const REGISTRATION_WAITLIST_MOVE_UP = 6;
}
