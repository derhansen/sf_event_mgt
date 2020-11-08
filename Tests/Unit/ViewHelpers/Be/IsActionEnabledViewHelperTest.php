<?php

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
    /**
     * @return array
     */
    public function viewHelperReturnsExpectedResultDataProvider()
    {
        return [
            'actionNotFoundInSettings' => [
                'unknown',
                [
                    'enabledActions' => []
                ],
                false,
                false
            ],
            'actionDisabledInSetting' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 0
                    ]
                ],
                false,
                false
            ],
            'actionEnabledInSettingNoAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1
                    ]
                ],
                false,
                false
            ],
            'actionEnabledInSettingAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1
                    ]
                ],
                true,
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider viewHelperReturnsExpectedResultDataProvider
     * @param string $action
     * @param array $settings
     * @param bool $access
     * @param bool $expected
     */
    public function viewHelperReturnsExpectedResult(string $action, array $settings, bool $access, bool $expected)
    {
        $viewHelper = new IsActionEnabledViewHelper();
        $viewHelper->setArguments([
            'action' => $action,
            'settings' => $settings
        ]);

        $mockBeUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->setMethods(['check'])->disableOriginalConstructor()->getMock();
        $mockBeUser->expects(self::any())->method('check')->willReturn($access);
        $GLOBALS['BE_USER'] = $mockBeUser;

        self::assertEquals($expected, $viewHelper->render());
    }
}
