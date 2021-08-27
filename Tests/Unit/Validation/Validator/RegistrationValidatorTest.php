<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Validation\Validator\RecaptchaValidator;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator.
 */
class RegistrationValidatorTest extends UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\Validation\Validator\StringLengthValidator
     */
    protected $validator;

    /**
     * @var string
     */
    protected $validatorClassName = RegistrationValidator::class;

    /**
     * Setup
     */
    protected function setup(): void
    {
        $this->validator = $this->getAccessibleMock(
            $this->validatorClassName,
            ['translateErrorMessage', 'getValidator'],
            [],
            '',
            false
        );
    }

    /**
     * Data provider for settings
     *
     * @return array
     */
    public function missingSettingsDataProvider()
    {
        return [
            'emptySettings' => [
                [],
                [],
                false
            ],
            'noRequiredFieldsSettings' => [
                ['registration' => ['requiredFields' => '']],
                [],
                false
            ],
        ];
    }

    /**
     * Executes all validation tests defines by the given data provider
     *
     * @test
     * @dataProvider missingSettingsDataProvider
     * @param mixed $settings
     * @param mixed $fields
     * @param mixed $expected
     */
    public function validatorReturnsTrueWhenArgumentsMissing($settings, $fields, $expected)
    {
        $registration = new Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');
        $registration->setEmail('email@domain.tld');

        foreach ($fields as $key => $value) {
            $registration->_setProperty($key, $value);
        }

        // Inject configuration and configurationManager
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->onlyMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManager->expects(self::once())->method('getConfiguration')->willReturn(
            $settings
        );
        $this->validator->injectConfigurationManager($configurationManager);

        self::assertEquals($expected, $this->validator->validate($registration)->hasErrors());
    }

    /**
     * Data provider for settings
     *
     * @return array
     */
    public function settingsDataProvider()
    {
        return [
            'requiredFieldsSettingsForCityIfCityNotSet' => [
                ['registration' => ['requiredFields' => 'city']],
                [],
                true,
                true
            ],
            'requiredFieldsSettingsForCityIfCitySet' => [
                ['registration' => ['requiredFields' => 'city']],
                ['city' => 'Some city'],
                false,
                false
            ],
            'requiredFieldsSettingsForCityAndZipWithWhitespace' => [
                ['registration' => ['requiredFields' => 'city, zip']],
                ['city' => 'Some city', 'zip' => '12345'],
                false,
                false
            ],
            'requiredFieldsSettingsForAccepttcBoolean' => [
                ['registration' => ['requiredFields' => 'accepttc']],
                ['accepttc' => false],
                false,
                false
            ],
            'requiredFieldsSettingsForUnknownProperty' => [
                ['registration' => ['requiredFields' => 'unknown_field']],
                [],
                false,
                false
            ],
            'requiredFieldsSettingsForRecaptchaIfRecatchaNotSet' => [
                ['registration' => ['requiredFields' => 'recaptcha']],
                [],
                true,
                true
            ],
            'requiredFieldsSettingsForRecaptchaIfRecatchaSet' => [
                ['registration' => ['requiredFields' => 'recaptcha']],
                ['recaptcha' => 'recaptcha-value'],
                false,
                false
            ],
        ];
    }

    /**
     * Executes all validation tests defined by the given data provider
     * Not really testing, just mocking if everything is called as expected
     *
     * @test
     * @dataProvider settingsDataProvider
     * @param mixed $settings
     * @param mixed $fields
     * @param mixed $hasErrors
     * @param mixed $expected
     */
    public function validatorReturnsExpectedResults($settings, $fields, $hasErrors, $expected)
    {
        $registration = new Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');
        $registration->setEmail('email@domain.tld');

        foreach ($fields as $key => $value) {
            $registration->_setProperty($key, $value);
        }

        // Inject configuration and configurationManager
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->onlyMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManager->expects(self::once())->method('getConfiguration')->willReturn(
            $settings
        );
        $this->validator->injectConfigurationManager($configurationManager);

        // Inject the object manager
        $validationError = $this->getMockBuilder(Error::class)
            ->disableOriginalConstructor()
            ->getMock();

        $validationResult = $this->getMockBuilder(Result::class)->getMock();
        $validationResult->expects(self::any())->method('hasErrors')->willReturn($hasErrors);
        $validationResult->expects(self::any())->method('getErrors')->willReturn(
            [$validationError]
        );

        $notEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $notEmptyValidator->expects(self::any())->method('validate')->willReturn(
            $validationResult
        );

        $booleanValidator = $this->getMockBuilder(BooleanValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $booleanValidator->expects(self::any())->method('validate')->willReturn(
            $validationResult
        );

        $recaptchaValidator = $this->getMockBuilder(RecaptchaValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $recaptchaValidator->expects(self::any())->method('validate')->willReturn(
            $validationResult
        );

        // Create a map of arguments to return values
        $map = [
            ['string', 'city', $notEmptyValidator],
            ['string', 'zip', $notEmptyValidator],
            ['string', 'recaptcha', $recaptchaValidator],
            ['boolean', 'accepttc', $booleanValidator]
        ];

        $this->validator->expects(self::any())->method('getValidator')->willReturnMap($map);

        self::assertEquals($expected, $this->validator->validate($registration)->hasErrors());
    }

    /**
     * Data povider for getValidator
     *
     * @return array
     */
    public function getValidatorDataProvider()
    {
        return [
            'string' => [
                'string',
                new NotEmptyValidator(),
                NotEmptyValidator::class
            ],
            'boolean' => [
                'boolean',
                new BooleanValidator(),
                BooleanValidator::class
            ]
        ];
    }

    /**
     * @test
     * @@dataProvider getValidatorDataProvider
     * @param mixed $type
     * @param mixed $returnedObject
     * @param mixed $expectedClass
     */
    public function getValidatorReturnsValidatorTest($type, $returnedObject, $expectedClass)
    {
        $validator = $this->getAccessibleMock($this->validatorClassName, ['dummy'], [], '', false);

        $objectManager = $this->getMockBuilder(ObjectManager::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $objectManager->expects(self::once())->method('get')->willReturn($returnedObject);
        $validator->injectObjectManager($objectManager);

        $result = $validator->_call('getValidator', $type, '');
        self::assertInstanceOf($expectedClass, $result);
    }
}
