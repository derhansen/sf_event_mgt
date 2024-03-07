<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Form\formDataProvider;

use DERHANSEN\SfEventMgt\Form\FormDataProvider\HideInlineRegistrations;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class HideInlineRegistrationsTest extends UnitTestCase
{
    public const LLL = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:backend.hideInlineRegistrations.';

    protected bool $resetSingletonInstances = true;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function addDataWhenHideInlineRegistrationsIsDisabled(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sf_event_mgt'] = [
            'hideInlineRegistrations' => 0,
            'hideInlineRegistrationsLimit' => 10,
        ];

        $input = [
            'tableName' => 'tx_sfeventmgt_domain_model_event',
            'databaseRow' => [
                'uid' => 1,
            ],
            'processedTca' => [
                'columns' => [
                    'registration' => [],
                    'registration_waitlist' => [],
                ],
            ],
        ];

        $expected = $input;
        self::assertEquals($expected, (new HideInlineRegistrations())->addData($input));
    }

    /**
     * @test
     */
    public function addDataWhenHideInlineRegistrationsIsEnabledAndLimitExceeded(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sf_event_mgt'] = [
            'hideInlineRegistrations' => 1,
            'hideInlineRegistrationsLimit' => 10,
        ];

        $input = [
            'tableName' => 'tx_sfeventmgt_domain_model_event',
            'databaseRow' => [
                'uid' => 1,
            ],
            'processedTca' => [
                'columns' => [
                    'title' => [],
                    'registration' => [],
                    'registration_waitlist' => [],
                ],
            ],
        ];

        $mockHideInlineRegistrations = $this->getMockBuilder(HideInlineRegistrations::class)
            ->onlyMethods(['getRegistrationCount'])
            ->getMock();
        $mockHideInlineRegistrations->expects(self::once())->method('getRegistrationCount')
            ->willReturn(11);

        $GLOBALS['LANG'] = $this->getMockBuilder(LanguageService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sL'])
            ->getMock();

        $GLOBALS['BE_USER'] = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();

        $expected = $input;
        unset($expected['processedTca']['columns']['registration']);
        unset($expected['processedTca']['columns']['registration_waitlist']);

        self::assertEquals($expected, $mockHideInlineRegistrations->addData($input));
    }
}
