<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Validation\Validator\CaptchaValidator;
use DERHANSEN\SfEventMgt\Validation\Validator\RecaptchaValidator;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageStore;
use TYPO3\CMS\Core\Localization\Locales;
use TYPO3\CMS\Core\Localization\LocalizationFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Error;
use TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator.
 */
class RegistrationValidatorTest extends UnitTestCase
{
    use ProphecyTrait;

    protected $resetSingletonInstances = true;

    /**
     * @var RegistrationValidator
     */
    protected $validator;

    /**
     * Setup
     */
    protected function setup(): void
    {
        $this->validator = $this->getAccessibleMock(
            RegistrationValidator::class,
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

        $this->validator->_set('settings', $settings);

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
                ['registration' => ['requiredFields' => 'captcha']],
                [],
                true,
                true
            ],
            'requiredFieldsSettingsForRecaptchaIfRecatchaSet' => [
                ['registration' => ['requiredFields' => 'captcha']],
                ['captcha' => 'captcha-value'],
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

        $this->validator->_set('settings', $settings);

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

        $recaptchaValidator = $this->getMockBuilder(CaptchaValidator::class)
            ->disableOriginalConstructor()
            ->getMock();
        $recaptchaValidator->expects(self::any())->method('validate')->willReturn(
            $validationResult
        );

        // Create a map of arguments to return values
        $map = [
            ['string', 'city', $notEmptyValidator],
            ['string', 'zip', $notEmptyValidator],
            ['string', 'captcha', $recaptchaValidator],
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
                '',
                NotEmptyValidator::class
            ],
            'boolean' => [
                'boolean',
                '',
                BooleanValidator::class
            ],
            'recaptcha' => [
                'string',
                'captcha',
                CaptchaValidator::class
            ]
        ];
    }

    /**
     * @test
     * @@dataProvider getValidatorDataProvider
     * @param string $type
     * @param string $field
     * @param string $expectedClass
     */
    public function getValidatorReturnsValidatorTest(string $type, string $field, string $expectedClass)
    {
        $validator = $this->getAccessibleMock(RegistrationValidator::class, ['dummy'], [], '', false);

        $configurationManager = $this->prophesize(ConfigurationManager::class);
        $configurationManager->getConfiguration(Argument::cetera())->willReturn([]);
        GeneralUtility::setSingletonInstance(ConfigurationManagerInterface::class, $configurationManager->reveal());

        $result = $validator->_call('getValidator', $type, $field);
        self::assertInstanceOf($expectedClass, $result);
    }
}
