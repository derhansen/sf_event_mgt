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
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\FieldType;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RegistrationFieldValidatorTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $GLOBALS['LANG'] = $this->getContainer()->get(LanguageServiceFactory::class)->create('default');

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    /**
     * @test
     */
    public function validatorHasNoErrorsWhenRegistrationHasNoFields(): void
    {
        $registration = new Registration();
        $subject = new RegistrationFieldValidator();
        $result = $subject->validate($registration);
        self::assertFalse($result->hasErrors());
    }

    public static function validatorDataProvider(): array
    {
        return [
            'required string field with no value' => [
                true,
                FieldType::TEXT,
                '',
                true,
            ],
            'required string field with value' => [
                true,
                FieldType::TEXT,
                'foo',
                false,
            ],
            'non required string field with no value' => [
                false,
                FieldType::TEXT,
                '',
                false,
            ],
            'non required string field with value' => [
                false,
                FieldType::TEXT,
                'foo',
                false,
            ],
            'required check field with no value' => [
                true,
                FieldType::CHECK,
                '',
                true,
            ],
            'required check field with no value (empty array)' => [
                true,
                FieldType::CHECK,
                json_encode(['']),
                true,
            ],
            'required check field with value (non empty array)' => [
                true,
                FieldType::CHECK,
                json_encode(['foo']),
                false,
            ],
            'required check field with value (non empty multi value array)' => [
                true,
                FieldType::CHECK,
                json_encode(['foo', 'bar']),
                false,
            ],
            'required check field with value (non empty multi value array - first item empty)' => [
                true,
                FieldType::CHECK,
                json_encode(['', 'bar']),
                false,
            ],
            'non required check field with no value' => [
                false,
                FieldType::CHECK,
                '',
                false,
            ],
            'non required check field with no value (empty array)' => [
                false,
                FieldType::CHECK,
                json_encode(['']),
                false,
            ],
            'required select field with no value' => [
                true,
                FieldType::SELECT,
                '',
                true,
            ],
            'required select field with no value (empty array)' => [
                true,
                FieldType::SELECT,
                json_encode(['']),
                true,
            ],
            'required select field with value (non empty array)' => [
                true,
                FieldType::SELECT,
                json_encode(['foo']),
                false,
            ],
            'required select field with value (non empty multi value array)' => [
                true,
                FieldType::SELECT,
                json_encode(['foo', 'bar']),
                false,
            ],
            'required select field with value (non empty multi value array - first item empty)' => [
                true,
                FieldType::SELECT,
                json_encode(['', 'bar']),
                false,
            ],
            'required radio field with no value' => [
                true,
                FieldType::RADIO,
                '',
                true,
            ],
            'required radio field with value' => [
                true,
                FieldType::RADIO,
                'foo',
                false,
            ],
            'non required radio field with no value' => [
                false,
                FieldType::RADIO,
                '',
                false,
            ],
            'non required radio field with value' => [
                false,
                FieldType::RADIO,
                'foo',
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validatorDataProvider
     */
    public function validatorReturnsExpectedResultForRegistrationField(
        bool $required,
        string $fieldType,
        string $fieldValue,
        bool $expectedResult
    ): void {
        $registrationField = new Registration\Field();
        $registrationField->setType($fieldType);
        $registrationField->setRequired($required);

        $event = new Event();
        $event->setTitle('Test event');
        $event->setEnableRegistration(true);
        $event->addRegistrationFields($registrationField);

        $registration = new Registration();
        $registration->setEvent($event);

        $registrationFieldValue = new Registration\FieldValue();
        $registrationFieldValue->setField($registrationField);
        $registrationFieldValue->setValue($fieldValue);

        $fieldValues = new ObjectStorage();
        $fieldValues->attach($registrationFieldValue);

        $registration->setFieldValues($fieldValues);

        $subject = new RegistrationFieldValidator();
        $result = $subject->validate($registration);

        self::assertEquals($expectedResult, $result->hasErrors());
    }
}
