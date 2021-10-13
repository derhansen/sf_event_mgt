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
use Prophecy\PhpUnit\ProphecyTrait;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\CalendarServiceTest.
 */
class HideInlineRegistrationsTest extends UnitTestCase
{
    use ProphecyTrait;

    const LLL = 'LLL:EXT:sf_event_mgt/Resources/Private/Language/locallang_be.xlf:backend.hideInlineRegistrations.';

    /**
     * @var bool Reset singletons created by subject
     */
    protected $resetSingletonInstances = true;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function addDataWhenHideInlineRegistrationsIsDisabled()
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
    public function addDataWhenHideInlineRegistrationsIsEnabledAndLimitExceeded()
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

        $languageServiceProphecy = $this->prophesize(LanguageService::class);
        $languageServiceProphecy->sL(self::LLL . 'description')->shouldBeCalled()->willReturn('desc');
        $languageServiceProphecy->sL(self::LLL . 'title')->shouldBeCalled()->willReturn('title');
        $GLOBALS['LANG'] = $languageServiceProphecy->reveal();

        $beUserProphecy = $this->prophesize(BackendUserAuthentication::class);
        $GLOBALS['BE_USER'] = $beUserProphecy->reveal();

        $expected = $input;
        unset($expected['processedTca']['columns']['registration']);
        unset($expected['processedTca']['columns']['registration_waitlist']);

        self::assertEquals($expected, $mockHideInlineRegistrations->addData($input));
    }
}
