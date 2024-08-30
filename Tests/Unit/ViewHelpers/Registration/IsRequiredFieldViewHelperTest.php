<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers\Registration;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\IsRequiredFieldViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for IsRequiredField viewhelper
 */
class IsRequiredFieldViewHelperTest extends UnitTestCase
{
    #[Test]
    public function viewHelperDoesNotRenderThenChildWhenNoFieldnameGiven(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => '',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip',
                ],
            ],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperDoesNotRenderThenChildWhenFieldnameNotInSettings(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'zip',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'firstname,lastname',
                ],
            ],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperRendersThenChildWhenFieldnameInSettings(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );
        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'zip',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip,otherfield',
                ],
            ],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperRenderThenChildForDefaultRequiredFieldnames(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );
        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'fieldname' => 'firstname',
            'settings' => [
                'registration' => [
                    'requiredFields' => 'zip,otherfield',
                ],
            ],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperDoesNotRenderThenChildWhenNoRegistrationFieldGiven(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );
        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => null,
            'settings' => [],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperDoesNotRenderThenChildWhenOptionalRegistrationFieldGiven(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );

        $optionalRegistrationField = new Field();
        $optionalRegistrationField->setRequired(false);

        $viewHelper->expects(self::never())->method('renderThenChild');
        $viewHelper->expects(self::once())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => $optionalRegistrationField,
            'settings' => [],
        ]);
        $viewHelper->render();
    }

    #[Test]
    public function viewHelperDoesRenderThenChildWhenRequiredRegistrationFieldGiven(): void
    {
        $viewHelper = $this->getAccessibleMock(
            IsRequiredFieldViewHelper::class,
            [
                'renderThenChild',
                'renderElseChild',
            ]
        );

        $requiredRegistrationField = new Field();
        $requiredRegistrationField->setRequired(true);

        $viewHelper->expects(self::once())->method('renderThenChild');
        $viewHelper->expects(self::never())->method('renderElseChild');
        $viewHelper->setArguments([
            'registrationField' => $requiredRegistrationField,
            'settings' => [],
        ]);
        $viewHelper->render();
    }
}
