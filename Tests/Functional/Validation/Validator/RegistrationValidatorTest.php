<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator.
 */
class RegistrationValidatorTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['LANG'] = $this->getContainer()->get(LanguageServiceFactory::class)->create('default');
    }

    /**
     * @test
     */
    public function registrationInvalidWhenDefaultFieldsNotSet()
    {
        $registration = new Registration();

        $subject = new RegistrationValidator();
        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertEquals(3, count($errors));
        self::assertTrue(isset($errors['firstname']));
        self::assertTrue(isset($errors['lastname']));
        self::assertTrue(isset($errors['email']));
    }

    /**
     * @test
     */
    public function registrationValidWhenDefaultFieldsSet()
    {
        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');

        $subject = new RegistrationValidator();

        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function registrationInvalidWhenCustomRequiredFieldsNotSet()
    {
        $configuration = [
            'extensionName' => 'SfEventMgt',
            'pluginName' => 'Pieventregistration',
            'settings.' => [
                'registration.' => [
                    'requiredFields' => 'city,zip,accepttc',
                ],
            ],
        ];

        $configurationManager = $this->getContainer()->get(ConfigurationManager::class);
        $configurationManager->setConfiguration($configuration);

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');

        $subject = new RegistrationValidator();

        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertTrue(isset($errors['city']));
        self::assertTrue(isset($errors['zip']));
        self::assertTrue(isset($errors['accepttc']));
    }

    /**
     * @test
     */
    public function registrationValidWhenCustomRequiredFieldsDoesNotExist()
    {
        $configuration = [
            'extensionName' => 'SfEventMgt',
            'pluginName' => 'Pieventregistration',
            'settings.' => [
                'registration.' => [
                    'requiredFields' => 'unknown',
                ],
            ],
        ];

        $configurationManager = $this->getContainer()->get(ConfigurationManager::class);
        $configurationManager->setConfiguration($configuration);

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');

        $subject = new RegistrationValidator();

        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }
}
