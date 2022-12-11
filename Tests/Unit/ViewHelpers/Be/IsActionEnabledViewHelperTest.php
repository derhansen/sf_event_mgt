<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use DERHANSEN\SfEventMgt\ViewHelpers\Be\IsActionEnabledViewHelper;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test for IsActionEnabledViewHelper
 */
class IsActionEnabledViewHelperTest extends UnitTestCase
{
    public function viewHelperReturnsExpectedResultDataProvider(): array
    {
        return [
            'actionNotFoundInSettings' => [
                'unknown',
                [
                    'enabledActions' => [],
                ],
                false,
                false,
            ],
            'actionDisabledInSetting' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 0,
                    ],
                ],
                false,
                false,
            ],
            'actionEnabledInSettingNoAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1,
                    ],
                ],
                false,
                false,
            ],
            'actionEnabledInSettingAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1,
                    ],
                ],
                true,
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsExpectedResultDataProvider
     */
    public function viewHelperReturnsExpectedResult(string $action, array $settings, bool $access, bool $expected): void
    {
        $viewHelper = new IsActionEnabledViewHelper();
        $viewHelper->setArguments([
            'action' => $action,
            'settings' => $settings,
        ]);

        $beUserMock = $this->getMockBuilder(BackendUserAuthentication::class)->disableOriginalConstructor()->getMock();
        $beUserMock->expects(self::any())->method('check')->willReturn($access);
        $GLOBALS['BE_USER'] = $beUserMock;

        self::assertEquals($expected, $viewHelper->render());
    }
}
