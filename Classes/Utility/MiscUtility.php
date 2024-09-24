<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Utility;

use DERHANSEN\SfEventMgt\Security\HashScope;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class MiscUtility
{
    /**
     * Returns chars extracted from a hmac for the challenge/response spam check
     */
    public static function getSpamCheckChallenge(int $eventUid): string
    {
        /** @var HashService $hashService */
        $hashService = GeneralUtility::makeInstance(HashService::class);
        $hmac = $hashService->hmac('event-' . $eventUid, HashScope::SpamCheckChallenge->value);
        $chars = preg_replace('/[0-9]+/', '', $hmac);

        return preg_replace_callback('/\w.?/', static function ($m) {
            return ucfirst($m[0]);
        }, $chars);
    }
}
