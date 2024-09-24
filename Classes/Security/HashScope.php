<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Security;

/**
 * Contains hashing specific scopes to be used as additional secret for HMACs.
 */
enum HashScope: string
{
    case PaymentAction = 'paymentAction';
    case RegistrationUid = 'registrationUid';
    case RegistrationHmac = 'registrationHmac';
    case EventUid = 'eventUid';
    case SaveRegistrationResult = 'saveRegistrationResult';
    case SpamCheckChallenge = 'sf_event_mgt';
}
