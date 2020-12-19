<?php

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
    /**
     * Viewhelper
     *
     * @var \DERHANSEN\SfEventMgt\ViewHelpers\Validation\ErrorClassViewHelper
     */
    protected $viewhelper;

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

    /**
     * @return array
     */
    public function fieldnameDataProvider()
    {
        return [
            'No fieldname' => [
                [],
                '',
                ''
            ],
            'No error for fieldname' => [
                [
                    'registration.lastname' => []
                ],
                'firstname',
                ''
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.firstname' => []
                ],
                'firstname',
                'error-class'
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.firstname' => []
                ],
                'firstname',
                'custom-class',
                'custom-class'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider fieldnameDataProvider
     * @param $validationErrors
     * @param $fieldname
     * @param $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForFieldname(
        $validationErrors,
        $fieldname,
        $expected,
        $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects(self::once())->method('getValidationErrors')
            ->willReturn($validationErrors);
        $this->viewhelper->setArguments([
            'fieldname' => $fieldname,
            'class' => $errorClass
        ]);
        self::assertEquals($expected, $this->viewhelper->render());
    }

    /**
     * @return array
     */
    public function registrationFieldDataProvider()
    {
        $mockField = $this->prophesize(Field::class);
        $mockField->getUid()->willReturn(2);

        return [
            'No registration field' => [
                [],
                '',
                ''
            ],
            'No error for registration field' => [
                [
                    'registration.fields.1' => []
                ],
                $mockField->reveal(),
                ''
            ],
            'Error for fieldname with default class name' => [
                [
                    'registration.fields.2' => []
                ],
                $mockField->reveal(),
                'error-class'
            ],
            'Error for fieldname with custom class name' => [
                [
                    'registration.fields.2' => []
                ],
                $mockField->reveal(),
                'custom-class',
                'custom-class'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider registrationFieldDataProvider
     * @param $validationErrors
     * @param $registrationField
     * @param $expected
     * @param string $errorClass
     */
    public function viewHelperReturnsExpectedStringForRegistrationField(
        $validationErrors,
        $registrationField,
        $expected,
        $errorClass = 'error-class'
    ) {
        $this->viewhelper->expects(self::once())->method('getValidationErrors')
            ->willReturn($validationErrors);
        $this->viewhelper->setArguments([
            'registrationField' => $registrationField,
            'class' => $errorClass
        ]);
        self::assertEquals($expected, $this->viewhelper->render());
    }
}
