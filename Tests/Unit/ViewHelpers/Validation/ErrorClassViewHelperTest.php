<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Validation\ErrorClassViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test for ErrorClassViewHelperTest
 */
class ErrorClassViewHelperTest extends UnitTestCase
{
    protected ErrorClassViewHelper $viewhelper;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->viewhelper = $this->getMockBuilder(ErrorClassViewHelper::class)
            ->onlyMethods(['getValidationErrors'])->getMock();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->viewhelper);
    }

    public function fieldnameDataProvider(): array
    {
        return [
            'No fieldname' => [
                [],
                '',
                '',
            ],
            'No error for fieldname' => [
                [
                    'registration.lastname' => [],
                ],
                'firstname',
                '',
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.firstname' => [],
                ],
                'firstname',
                'error-class',
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.firstname' => [],
                ],
                'firstname',
                'custom-class',
                'custom-class',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider fieldnameDataProvider
     * @param array $validationErrors
     * @param string $fieldname
     * @param string $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForFieldname(
        array $validationErrors,
        string $fieldname,
        string $expected,
        string $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects(self::once())->method('getValidationErrors')
            ->willReturn($validationErrors);
        $this->viewhelper->setArguments([
            'fieldname' => $fieldname,
            'class' => $errorClass,
        ]);
        self::assertEquals($expected, $this->viewhelper->render());
    }

    public function registrationFieldDataProvider(): array
    {
        $mockField = $this->getMockBuilder(Field::class)->getMock();
        $mockField->expects(self::any())->method('getUid')->willReturn(2);

        return [
            'No registration field' => [
                [],
                null,
                '',
            ],
            'No error for registration field' => [
                [
                    'registration.fields.1' => [],
                ],
                $mockField,
                '',
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.fields.2' => [],
                ],
                $mockField,
                'error-class',
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.fields.2' => [],
                ],
                $mockField,
                'custom-class',
                'custom-class',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider registrationFieldDataProvider
     * @param array $validationErrors
     * @param ?Field $registrationField
     * @param string $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForRegistrationField(
        array $validationErrors,
        ?Field $registrationField,
        string $expected,
        string $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects(self::once())->method('getValidationErrors')
            ->willReturn($validationErrors);
        $this->viewhelper->setArguments([
            'registrationField' => $registrationField,
            'class' => $errorClass,
        ]);
        self::assertEquals($expected, $this->viewhelper->render());
    }
}
