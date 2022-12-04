<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Validation\Validator;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Utility\FieldValueType;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator.
 */
class RegistrationFieldValidatorTest extends UnitTestCase
{
    protected RegistrationFieldValidator $validator;

    /**
     * Setup
     */
    protected function setup(): void
    {
        $this->validator = $this->getAccessibleMock(
            RegistrationFieldValidator::class,
            ['translateErrorMessage', 'getNotEmptyValidator'],
            [],
            '',
            false
        );
    }

    /**
     * @test
     */
    public function validatorHasNoErrorsWhenRegistrationHasNoFieldValues()
    {
        $this->markTestSkipped('Migrate to functional test');
        $mockEvent = $this->getMockBuilder(Event::class)
            ->onlyMethods(['getRegistrationFields'])
            ->getMock();
        $mockEvent->expects(self::once())->method('getRegistrationFields')
            ->willReturn(new ObjectStorage());

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::once())->method('getFieldValues')
            ->willReturn(new ObjectStorage());

        self::assertFalse($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasErrorsWhenRequiredRegistrationFieldIsEmpty()
    {
        $this->markTestSkipped('Migrate to functional test');
        $fieldUid = 1;

        $mockRegistrationField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegistrationField->expects(self::once())->method('getRequired')->willReturn(true);
        $mockRegistrationField->expects(self::any())->method('getUid')->willReturn($fieldUid);
        $registrationFieldObjectStorage = new ObjectStorage();
        $registrationFieldObjectStorage->attach($mockRegistrationField);

        $mockEvent = $this->getMockBuilder(Event::class)
            ->onlyMethods(['getRegistrationFields'])
            ->getMock();
        $mockEvent->expects(self::any())->method('getRegistrationFields')
            ->willReturn($registrationFieldObjectStorage);

        $mockFieldValue = $this->getMockBuilder(Registration\FieldValue::class)->getMock();
        $mockFieldValue->expects(self::any())->method('getField')->willReturn($mockRegistrationField);
        $fieldValueObjectStorage = new ObjectStorage();
        $fieldValueObjectStorage->attach($mockFieldValue);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::any())->method('getFieldValues')
            ->willReturn($fieldValueObjectStorage);

        $mockNotEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->onlyMethods(['translateErrorMessage'])
            ->getMock();
        $this->validator->expects(self::any())->method('getNotEmptyValidator')
            ->willReturn($mockNotEmptyValidator);

        self::assertTrue($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasErrorsWhenRequiredRegistrationCheckboxFieldIsEmpty()
    {
        $this->markTestSkipped('Migrate to functional test');
        $fieldUid = 1;

        $mockRegistrationField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegistrationField->expects(self::once())->method('getRequired')->willReturn(true);
        $mockRegistrationField->expects(self::any())->method('getUid')->willReturn($fieldUid);
        $mockRegistrationField->expects(self::any())->method('getValueType')
            ->willReturn(FieldValueType::TYPE_ARRAY);
        $registrationFieldObjectStorage = new ObjectStorage();
        $registrationFieldObjectStorage->attach($mockRegistrationField);

        $mockEvent = $this->getMockBuilder(Event::class)
            ->onlyMethods(['getRegistrationFields'])
            ->getMock();
        $mockEvent->expects(self::any())->method('getRegistrationFields')
            ->willReturn($registrationFieldObjectStorage);

        $fieldValue = new Registration\FieldValue();
        $fieldValue->setField($mockRegistrationField);
        $fieldValue->setValueType(FieldValueType::TYPE_ARRAY);
        $fieldValueObjectStorage = new ObjectStorage();
        $fieldValueObjectStorage->attach($fieldValue);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::any())->method('getFieldValues')
            ->willReturn($fieldValueObjectStorage);

        $mockNotEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->onlyMethods(['translateErrorMessage'])
            ->getMock();
        $this->validator->expects(self::any())->method('getNotEmptyValidator')
            ->willReturn($mockNotEmptyValidator);

        self::assertTrue($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasNoErrorsWhenNonRequiredRegistrationFieldIsEmpty()
    {
        $this->markTestSkipped('Migrate to functional test');
        $fieldUid = 1;

        $mockRegistrationField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegistrationField->expects(self::once())->method('getRequired')->willReturn(false);
        $mockRegistrationField->expects(self::any())->method('getUid')->willReturn($fieldUid);
        $registrationFieldObjectStorage = new ObjectStorage();
        $registrationFieldObjectStorage->attach($mockRegistrationField);

        $mockEvent = $this->getMockBuilder(Event::class)->onlyMethods(['getRegistrationFields'])->getMock();
        $mockEvent->expects(self::any())->method('getRegistrationFields')
            ->willReturn($registrationFieldObjectStorage);

        $mockFieldValue = $this->getMockBuilder(Registration\FieldValue::class)->getMock();
        $mockFieldValue->expects(self::any())->method('getField')->willReturn($mockRegistrationField);
        $fieldValueObjectStorage = new ObjectStorage();
        $fieldValueObjectStorage->attach($mockFieldValue);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::any())->method('getEvent')->willReturn($mockEvent);
        $mockRegistration->expects(self::any())->method('getFieldValues')
            ->willReturn($fieldValueObjectStorage);

        $mockNotEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->onlyMethods(['translateErrorMessage'])
            ->getMock();
        $this->validator->expects(self::any())->method('getNotEmptyValidator')
            ->willReturn($mockNotEmptyValidator);

        self::assertFalse($this->validator->validate($mockRegistration)->hasErrors());
    }
}
