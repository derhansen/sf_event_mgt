<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\IsRequiredFieldViewHelper;
use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for IsRequiredField viewhelper
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class IsRequiredFieldViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function viewHelperDoesNotRenderThenChildWhenNoFieldnameGiven()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => '',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip'
                ]
            ]
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperDoesNotRenderThenChildWhenFieldnameNotInSettings()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'zip',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'firstname,lastname'
                ]
            ]
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperRendersThenChildWhenFieldnameInSettings()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );
        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'zip',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip,otherfield'
                ]
            ]
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperRenderThenChildForDefaultRequiredFieldnames()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );
        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip,otherfield'
                ]
            ]
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperDoesNotRenderThenChildWhenNoRegistrationFieldGiven()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => null,
            'settings' => []
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperDoesNotRenderThenChildWhenOptionalRegistrationFieldGiven()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );

        $optionalRegistrationField = new Field();
        $optionalRegistrationField->setRequired(false);

        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => $optionalRegistrationField,
            'settings' => []
        ]);
        $viewHelper->render();
    }

    /**
     * @test
     */
    public function viewHelperDoesRenderThenChildWhenRequiredRegistrationFieldGiven()
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild'
            ]
        );

        $requiredRegistrationField = new Field();
        $requiredRegistrationField->setRequired(true);

        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => $requiredRegistrationField,
            'settings' => []
        ]);
        $viewHelper->render();
    }
}
