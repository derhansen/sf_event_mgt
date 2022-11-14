<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\EventPlausabilityService;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\EventPlausabilityService.
 */
class EventPlausabilityServiceTest extends UnitTestCase
{
    protected $resetSingletonInstances = true;

    public function isStartDateBeforeEndDateDataProvider(): array
    {
        return [
            'no dates' => [
                0,
                0,
                true,
            ],
            'startdate only' => [
               strtotime('2021-03-01T10:00:00+00:00'),
                0,
                true,
            ],
            'startdate before enddate' => [
                strtotime('2021-03-01T10:00:00+00:00'),
                strtotime('2021-03-01T11:00:00+00:00'),
                true,
            ],
            'enddate before startdate' => [
                strtotime('2021-03-01T11:00:00+00:00'),
                strtotime('2021-03-01T10:00:00+00:00'),
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isStartDateBeforeEndDateDataProvider
     */
    public function isStartDateBeforeEndDateReturnsExpectedResults($startdate, $enddate, $expected)
    {
        $service = $this->getAccessibleMock(EventPlausabilityService::class, ['dummy'], [], '', false);
        self::assertEquals($expected, $service->_call('isStartDateBeforeEndDate', $startdate, $enddate));
    }

    /**
     * @test
     */
    public function verifyOrganisatorConfigurationWithNoOrganisatorAndDisabledRegistrationAddsNoFlashMessage()
    {
        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::never())->method('sL');
        $GLOBALS['LANG'] = $languageService;

        $databaseRow = [
            'enable_registration' => 0,
            'notify_organisator' => 1,
        ];

        $service = new EventPlausabilityService();
        $service->verifyOrganisatorConfiguration($databaseRow);
    }

    /**
     * @test
     */
    public function verifyOrganisatorConfigurationWithNoOrganisatorAddsFlashMessage()
    {
        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::atLeastOnce())->method('sL');
        $GLOBALS['LANG'] = $languageService;

        $databaseRow = [
            'enable_registration' => 1,
            'notify_organisator' => 1,
        ];

        $service = new EventPlausabilityService();
        $service->verifyOrganisatorConfiguration($databaseRow);
    }

    /**
     * @test
     */
    public function verifyOrganisatorConfigurationWithOrganisatorAndNoEmailAddsFlashMessage()
    {
        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::atLeastOnce())->method('sL');
        $GLOBALS['LANG'] = $languageService;

        $databaseRow = [
            'enable_registration' => 1,
            'notify_organisator' => 1,
            'organisator' => [
                [
                    'row' => [
                        'email' => '',
                    ],
                ],
            ],
        ];

        $service = new EventPlausabilityService();
        $service->verifyOrganisatorConfiguration($databaseRow);
    }

    /**
     * @test
     */
    public function verifyOrganisatorConfigurationWithOrganisatorAndValidEmailAddsNoFlashMessage()
    {
        $languageService = $this->createMock(LanguageService::class);
        $languageService->expects(self::never())->method('sL');
        $GLOBALS['LANG'] = $languageService;

        $databaseRow = [
            'enable_registration' => 0,
            'notify_organisator' => 1,
            'organisator' => [
                [
                    'row' => [
                        'email' => 'email@domain.tld',
                    ],
                ],
            ],
        ];

        $service = new EventPlausabilityService();
        $service->verifyOrganisatorConfiguration($databaseRow);
    }
}
