<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Validation\Validator;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;

/**
 * Test case for class DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class RegistrationFieldValidatorTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Validation\Validator\RegistrationFieldValidator
     */
    protected $validator;

    /**
     * Setup
     *
     * @return void
     */
    public function setup()
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
    public function validatorHasNoErrorsWhenRegistrationHasNoEvent()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->once())->method('getEvent')->will($this->returnValue(null));
        $this->assertFalse($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasNoErrorsWhenRegistrationHasNoFieldValues()
    {
        $mockEvent = $this->getMockBuilder(Event::class)
            ->setMethods(['getRegistrationFields'])
            ->getMock();
        $mockEvent->expects($this->once())->method('getRegistrationFields')
            ->will($this->returnValue(new ObjectStorage()));

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->once())->method('getFieldValues')
            ->will($this->returnValue(new ObjectStorage()));

        $this->assertFalse($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasErrorsWhenRequiredRegistrationFieldIsEmpty()
    {
        $fieldUid = 1;

        $mockRegistrationField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegistrationField->expects($this->once())->method('getRequired')->will($this->returnValue(true));
        $mockRegistrationField->expects($this->any())->method('getUid')->will($this->returnValue($fieldUid));
        $registrationFieldObjectStorage = new ObjectStorage();
        $registrationFieldObjectStorage->attach($mockRegistrationField);

        $mockEvent = $this->getMockBuilder(Event::class)
            ->setMethods(['getRegistrationFields'])
            ->getMock();
        $mockEvent->expects($this->any())->method('getRegistrationFields')
            ->will($this->returnValue($registrationFieldObjectStorage));

        $mockFieldValue = $this->getMockBuilder(Registration\FieldValue::class)->getMock();
        $mockFieldValue->expects($this->any())->method('getField')->will($this->returnValue($mockRegistrationField));
        $fieldValueObjectStorage = new ObjectStorage();
        $fieldValueObjectStorage->attach($mockFieldValue);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->any())->method('getFieldValues')
            ->will($this->returnValue($fieldValueObjectStorage));

        $mockNotEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->setMethods(['translateErrorMessage'])
            ->getMock();
        $this->validator->expects($this->any())->method('getNotEmptyValidator')
            ->will($this->returnValue($mockNotEmptyValidator));

        $this->assertTrue($this->validator->validate($mockRegistration)->hasErrors());
    }

    /**
     * @test
     */
    public function validatorHasNoErrorsWhenNonRequiredRegistrationFieldIsEmpty()
    {
        $fieldUid = 1;

        $mockRegistrationField = $this->getMockBuilder(Registration\Field::class)->getMock();
        $mockRegistrationField->expects($this->once())->method('getRequired')->will($this->returnValue(false));
        $mockRegistrationField->expects($this->any())->method('getUid')->will($this->returnValue($fieldUid));
        $registrationFieldObjectStorage = new ObjectStorage();
        $registrationFieldObjectStorage->attach($mockRegistrationField);

        $mockEvent = $this->getMockBuilder(Event::class)->setMethods(['getRegistrationFields'])->getMock();
        $mockEvent->expects($this->any())->method('getRegistrationFields')
            ->will($this->returnValue($registrationFieldObjectStorage));

        $mockFieldValue = $this->getMockBuilder(Registration\FieldValue::class)->getMock();
        $mockFieldValue->expects($this->any())->method('getField')->will($this->returnValue($mockRegistrationField));
        $fieldValueObjectStorage = new ObjectStorage();
        $fieldValueObjectStorage->attach($mockFieldValue);

        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects($this->any())->method('getEvent')->will($this->returnValue($mockEvent));
        $mockRegistration->expects($this->any())->method('getFieldValues')
            ->will($this->returnValue($fieldValueObjectStorage));

        $mockNotEmptyValidator = $this->getMockBuilder(NotEmptyValidator::class)
            ->setMethods(['translateErrorMessage'])
            ->getMock();
        $this->validator->expects($this->any())->method('getNotEmptyValidator')
            ->will($this->returnValue($mockNotEmptyValidator));

        $this->assertFalse($this->validator->validate($mockRegistration)->hasErrors());
    }
}
