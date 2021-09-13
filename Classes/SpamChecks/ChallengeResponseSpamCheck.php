<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\SpamChecks;

use DERHANSEN\SfEventMgt\Utility\MiscUtility;

/**
 * ChallengeResponseSpamCheck
 */
class ChallengeResponseSpamCheck extends AbstractSpamCheck
{
    /**
     * Checks, if the cr-response field matches the expected ROT13 encrypted/obfuscated string.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        if (!isset($this->arguments['registration']['cr-response'])) {
            return true;
        }

        $challenge =  MiscUtility::getSpamCheckChallenge((int)$this->arguments['event']);
        $originalChallenge = ($this->configuration['prefix'] ?? 'SfEventMgt') .
            $challenge . ($this->configuration['postfix'] ?? 'TYPO3');

        $expectedResponse = str_rot13($originalChallenge);
        $response = $this->arguments['registration']['cr-response'] ?? '';

        return $expectedResponse !== $response;
    }
}
