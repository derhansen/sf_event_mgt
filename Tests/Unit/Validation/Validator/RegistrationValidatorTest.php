<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Validation\Validator;

    /*
     * This file is part of the TYPO3 CMS project.
     *
     * It is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License, either version 2
     * of the License, or any later version.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * The TYPO3 project - inspiring people to share!
     */

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationValidator.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationValidatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \TYPO3\CMS\Extbase\Validation\Validator\StringLengthValidator
     */
    protected $validator;

    /**
     * @var string
     */
    protected $validatorClassName = 'DERHANSEN\\SfEventMgt\\Validation\\Validator\\RegistrationValidator';

    /**
     * Setup
     *
     * @return void
     */
    public function setup()
    {
        $this->validator = $this->getAccessibleMock($this->validatorClassName,
            ['translateErrorMessage', 'getValidator'],
            [], '', false);
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
     */
    public function validatorReturnsTrueWhenArgumentsMissing($settings, $fields, $expected)
    {
        $registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');
        $registration->setEmail('email@domain.tld');

        foreach ($fields as $key => $value) {
            $registration->_setProperty($key, $value);
        }

        // Inject configuration and configurationManager
        $configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
            ['getConfiguration'], [], '', false);
        $configurationManager->expects($this->once())->method('getConfiguration')->will(
            $this->returnValue($settings));
        $this->inject($this->validator, 'configurationManager', $configurationManager);

        $this->assertEquals($expected, $this->validator->validate($registration)->hasErrors());
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
     */
    public function validatorReturnsExpectedResults($settings, $fields, $hasErrors, $expected)
    {
        $registration = new \DERHANSEN\SfEventMgt\Domain\Model\Registration();
        $registration->setFirstname('John');
        $registration->setLastname('Doe');
        $registration->setEmail('email@domain.tld');

        foreach ($fields as $key => $value) {
            $registration->_setProperty($key, $value);
        }

        // Inject configuration and configurationManager
        $configurationManager = $this->getMock('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager',
            ['getConfiguration'], [], '', false);
        $configurationManager->expects($this->once())->method('getConfiguration')->will(
            $this->returnValue($settings));
        $this->inject($this->validator, 'configurationManager', $configurationManager);

        // Inject the object manager
        $validationError = $this->getMock('TYPO3\\CMS\\Extbase\\Error\\Error', [], [], '', false);

        $validationResult = $this->getMock('TYPO3\\CMS\\Extbase\\Error\\Result', [], [], '', false);
        $validationResult->expects($this->any())->method('hasErrors')->will($this->returnValue($hasErrors));
        $validationResult->expects($this->any())->method('getErrors')->will(
            $this->returnValue([$validationError]));

        $notEmptyValidator = $this->getMock('TYPO3\\CMS\\Extbase\\Validation\\Validator\\NotEmptyValidator',
            [], [], '', false);
        $notEmptyValidator->expects($this->any())->method('validate')->will($this->returnValue(
            $validationResult));

        $booleanValidator = $this->getMock('TYPO3\\CMS\\Extbase\\Validation\\Validator\\BooleanValidator',
            [], [], '', false);
        $booleanValidator->expects($this->any())->method('validate')->will($this->returnValue(
            $validationResult));

        $recaptchaValidator = $this->getMock('DERHANSEN\\SfEventMgt\\Validation\\Validator\\RecaptchaValidator',
            [], [], '', false);
        $recaptchaValidator->expects($this->any())->method('validate')->will($this->returnValue(
            $validationResult));


        // Create a map of arguments to return values
        $map = [
            ['string', 'city', $notEmptyValidator],
            ['string', 'zip', $notEmptyValidator],
            ['string', 'recaptcha', $recaptchaValidator],
            ['boolean', 'accepttc', $booleanValidator]
        ];

        $this->validator->expects($this->any())->method('getValidator')->will($this->returnValueMap($map));

        $this->assertEquals($expected, $this->validator->validate($registration)->hasErrors());
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
                new \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator(),
                '\TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator'
            ],
            'boolean' => [
                'boolean',
                new \TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator(),
                '\TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator'
            ]
        ];
    }

    /**
     * @test
     * @@dataProvider getValidatorDataProvider
     */
    public function getValidatorReturnsValidatorTest($type, $returnedObject, $expectedClass)
    {
        $validator = $this->getAccessibleMock($this->validatorClassName, ['dummy'], [], '', false);

        $objectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManager',
            ['get'], [], '', false);
        $objectManager->expects($this->once())->method('get')->will($this->returnValue($returnedObject));
        $this->inject($validator, 'objectManager', $objectManager);

        $result = $validator->_call('getValidator', $type, '');
        $this->assertInstanceOf($expectedClass, $result);
    }
}
