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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventPlausabilityServiceTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    public static function isStartDateBeforeEndDateDataProvider(): array
    {
        return [
            'no dates' => [
                null,
                null,
                true,
            ],
            'startdate only' => [
                new \DateTimeImmutable('2021-03-01T10:00:00+00:00'),
                null,
                true,
            ],
            'startdate before enddate' => [
                new \DateTimeImmutable('2021-03-01T10:00:00+00:00'),
                new \DateTimeImmutable('2021-03-01T11:00:00+00:00'),
                true,
            ],
            'enddate before startdate' => [
                new \DateTimeImmutable('2021-03-01T11:00:00+00:00'),
                new \DateTimeImmutable('2021-03-01T10:00:00+00:00'),
                false,
            ],
        ];
    }

    #[DataProvider('isStartDateBeforeEndDateDataProvider')]
    #[Test]
    public function isStartDateBeforeEndDateReturnsExpectedResults(
        ?\DateTimeImmutable $startdate = null,
        ?\DateTimeImmutable $enddate = null,
        bool $expected = false
    ): void {
        $service = $this->getAccessibleMock(EventPlausabilityService::class, null, [], '', false);
        self::assertEquals($expected, $service->_call('isStartDateBeforeEndDate', $startdate, $enddate));
    }

    #[Test]
    public function verifyOrganisatorConfigurationWithNoOrganisatorAndDisabledRegistrationAddsNoFlashMessage(): void
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

    #[Test]
    public function verifyOrganisatorConfigurationWithNoOrganisatorAddsFlashMessage(): void
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

    #[Test]
    public function verifyOrganisatorConfigurationWithOrganisatorAndNoEmailAddsFlashMessage(): void
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

    #[Test]
    public function verifyOrganisatorConfigurationWithOrganisatorAndValidEmailAddsNoFlashMessage(): void
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
