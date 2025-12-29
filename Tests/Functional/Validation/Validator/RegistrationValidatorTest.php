<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\PriceOption;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RegistrationValidatorTest extends FunctionalTestCase
{
    protected ServerRequestInterface $request;

    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['LANG'] = $this->getContainer()->get(LanguageServiceFactory::class)->create('default');

        $this->request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $this->request;
    }

    #[Test]
    public function registrationInvalidWhenDefaultFieldsNotSet(): void
    {
        $registration = new Registration();

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);
        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertCount(3, $errors);
        self::assertTrue(isset($errors['firstname']));
        self::assertTrue(isset($errors['lastname']));
        self::assertTrue(isset($errors['email']));
    }

    #[Test]
    public function registrationValidWhenDefaultFieldsSet(): void
    {
        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }

    #[Test]
    public function registrationInvalidWhenCustomRequiredFieldsNotSet(): void
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

        $subject = new RegistrationValidator(
            $configurationManager,
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertTrue(isset($errors['city']));
        self::assertTrue(isset($errors['zip']));
        self::assertTrue(isset($errors['accepttc']));
    }

    #[Test]
    public function registrationValidWhenCustomRequiredFieldsDoesNotExist(): void
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

        $subject = new RegistrationValidator(
            $configurationManager,
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }

    #[Test]
    public function registrationInvalidWhenPriceOptionIsRequired(): void
    {
        $priceOption = new PriceOption();
        $priceOption->setPrice(10.00);

        $event = new Event();
        $event->addPriceOptions($priceOption);

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');
        $registration->setEvent($event);

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertCount(1, $errors);
        self::assertTrue(isset($errors['priceOption']));
    }

    #[Test]
    public function registrationInvalidWhenPriceOptionDoesNotBelongToEvent(): void
    {
        $priceOption = new PriceOption();
        $priceOption->_setProperty('uid', 1);
        $priceOption->setPrice(10.00);

        $event = new Event();
        $event->addPriceOptions($priceOption);

        $invalidPriceOption = new PriceOption();
        $invalidPriceOption->_setProperty('uid', 2);
        $invalidPriceOption->setPrice(20.00);

        $registration = new Registration();
        $registration->setPriceOption($invalidPriceOption);
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');
        $registration->setEvent($event);

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertCount(1, $errors);
        self::assertTrue(isset($errors['priceOption']));
        self::assertEquals(1727776820, $errors['priceOption']->getFirstError()->getCode());
    }

    #[Test]
    public function registrationInvalidWhenPaymentMethodIsRequired(): void
    {
        $priceOption = new PriceOption();
        $priceOption->setPrice(10.00);

        $event = new Event();
        $event->setEnablePayment(true);
        $event->addPriceOptions($priceOption);

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');
        $registration->setEvent($event);
        $registration->setPriceOption($priceOption);

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertTrue($result->hasErrors());
        $errors = $result->getSubResults();
        self::assertCount(1, $errors);
        self::assertTrue(isset($errors['paymentmethod']));
    }

    #[Test]
    public function registrationValidWhenPaymentMethodIsRequiredAndProvided(): void
    {
        $priceOption = new PriceOption();
        $priceOption->setPrice(10.00);

        $event = new Event();
        $event->setEnablePayment(true);
        $event->addPriceOptions($priceOption);

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('derhansen@gmail.com');
        $registration->setEvent($event);
        $registration->setPriceOption($priceOption);
        $registration->setPaymentMethod('invoice');

        $subject = new RegistrationValidator(
            $this->get(ConfigurationManager::class),
            $this->get(EventDispatcherInterface::class)
        );
        $subject->setRequest($this->request);

        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }
}
