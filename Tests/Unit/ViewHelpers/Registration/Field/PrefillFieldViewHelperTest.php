<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for prefillField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewHelperReturnsFieldDefaultValueIfNoOriginalRequest()
    {
        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('getOriginalRequest')->willReturn(null);

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($mockRequest);

        $field = new Field();
        $field->setDefaultValue('Default');

        $viewHelper->_set('arguments', ['registrationField' => $field]);
        $actual = $viewHelper->_callRef('render');
        self::assertSame('Default', $actual);
    }

    /**
     * @return array
     */
    public function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider()
    {
        return [
            'submitted value returned' => [
                1,
                [
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                'Submitted value'
            ],
            'empty value returned if not found' => [
                2,
                [
                    'field' => '1',
                    'value' => 'Submitted value'
                ],
                ''
            ]
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider
     * @param mixed $fieldUid
     * @param mixed $fieldValues
     * @param mixed $expected
     */
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist($fieldUid, $fieldValues, $expected)
    {
        $arguments = [
            'registration' => [
                'fieldValues' => [$fieldValues]
            ]
        ];

        $mockOriginalRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getArguments'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockOriginalRequest->expects(self::once())->method('getArguments')->willReturn($arguments);

        $mockRequest = $this->getMockBuilder(Request::class)
            ->setMethods(['getOriginalRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('getOriginalRequest')
            ->willReturn($mockOriginalRequest);

        $viewHelper = $this->getAccessibleMock(PrefillFieldViewHelper::class, ['getRequest'], [], '', false);
        $viewHelper->expects(self::once())->method('getRequest')->willReturn($mockRequest);

        $mockField = $this->getMockBuilder(Field::class)->getMock();
        $mockField->expects(self::once())->method('getUid')->willReturn($fieldUid);

        $viewHelper->_set('arguments', ['registrationField' => $mockField]);
        $actual = $viewHelper->_callRef('render');
        self::assertSame($expected, $actual);
    }
}
