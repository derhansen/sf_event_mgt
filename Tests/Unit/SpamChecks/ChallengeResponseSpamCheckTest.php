<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\SpamChecks;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\SpamChecks\ChallengeResponseSpamCheck;
use DERHANSEN\SfEventMgt\Utility\MiscUtility;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\SpamChecks\ChallengeResponseSpamCheckTest
 */
class ChallengeResponseSpamCheckTest extends UnitTestCase
{
    /**
     * @test
     */
    public function checkIsFailedWhenArgumentNotSet()
    {
        $registration = new Registration();
        $settings = [];
        $arguments = [];
        $configuration = [];

        $check = new ChallengeResponseSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertTrue($check->isFailed());
    }

    /**
     * @test
     */
    public function checkIsFailedWhenChallengeResponseDoesNotMatch()
    {
        $registration = new Registration();
        $settings = [];
        $arguments = [
            'registration' => [
                'cr-response' => ''
            ]
        ];
        $configuration = [
            'prefix' => 'test',
            'postfix' => 'test'
        ];

        $check = new ChallengeResponseSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertTrue($check->isFailed());
    }

    /**
     * @test
     */
    public function checkIsSuccessfulWhenChallengeResponseIsValid()
    {
        $registration = new Registration();

        $settings = [];
        $arguments = [
            'event' => 5,
            'registration' => [
                'cr-response' => str_rot13('test' . MiscUtility::getSpamCheckChallenge(5) . 'test')
            ]
        ];
        $configuration = [
            'prefix' => 'test',
            'postfix' => 'test'
        ];

        $check = new ChallengeResponseSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertFalse($check->isFailed());
    }
}
