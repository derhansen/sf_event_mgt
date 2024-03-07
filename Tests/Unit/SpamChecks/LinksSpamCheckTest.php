<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\SpamChecks;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\SpamChecks\LinkSpamCheck;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class LinksSpamCheckTest extends UnitTestCase
{
    /**
     * @test
     */
    public function fallbackToDefaultMaxAmountOfLinksIfConfigurationEmpty(): void
    {
        $registration = new Registration();
        $registration->setFirstname('https://www.derhansen.com');
        $registration->setLastname('https://www.derhansen.com');
        $registration->setAddress('https://www.derhansen.com');
        $settings = [];
        $arguments = [];
        $configuration = [];

        $check = new LinkSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertTrue($check->isFailed());
    }

    /**
     * @test
     */
    public function checkIsFailedWhenConfiguredAmountOfLinksIsExceeded(): void
    {
        $registration = new Registration();
        $registration->setFirstname('https://www.derhansen.com');
        $registration->setLastname('https://www.derhansen.com');
        $registration->setAddress('https://www.derhansen.com');
        $settings = [];
        $arguments = [];
        $configuration = [
            'maxAmountOfLinks' => 2,
        ];

        $check = new LinkSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertTrue($check->isFailed());
    }

    /**
     * @test
     */
    public function checkIsFailedWhenConfiguredAmountOfLinksIsExceededInRegistrationField(): void
    {
        $registrationFieldValue = new Registration\FieldValue();
        $registrationFieldValue->setValue('https://www.derhansen.com');
        $objectStorage = new ObjectStorage();
        $objectStorage->attach($registrationFieldValue);
        $registration = new Registration();
        $registration->setFirstname('https://www.typo3.org');
        $registration->setFieldValues($objectStorage);
        $settings = [];
        $arguments = [];
        $configuration = [
            'maxAmountOfLinks' => 1,
        ];

        $check = new LinkSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertTrue($check->isFailed());
    }

    /**
     * @test
     */
    public function checkIsNotFailedWhenConfiguredAmountOfLinksIsNotExceeded(): void
    {
        $registration = new Registration();
        $registration->setFirstname('https://www.derhansen.com');
        $settings = [];
        $arguments = [];
        $configuration = [
            'maxAmountOfLinks' => 1,
        ];

        $check = new LinkSpamCheck($registration, $settings, $arguments, $configuration);
        self::assertFalse($check->isFailed());
    }
}
