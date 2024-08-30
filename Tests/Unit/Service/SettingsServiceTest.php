<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Service\SettingsService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class SettingsServiceTest extends UnitTestCase
{
    protected SettingsService $subject;

    protected function setUp(): void
    {
        $this->subject = new SettingsService();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    public static function customNotificationsSettingsDataProvider(): array
    {
        return [
            'emptySettings' => [
                [],
                [],
            ],
            'oneEntry' => [
                [
                    'notification' => [
                        'customNotifications' => [
                            'firstEntry' => [
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject',
                            ],
                        ],
                    ],
                ],
                ['firstEntry' => 'First title'],
            ],
            'twoEntry' => [
                [
                    'notification' => [
                        'customNotifications' => [
                            'firstEntry' => [
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject',
                            ],
                            'secondEntry' => [
                                'title' => 'Second title',
                                'template' => 'Second template',
                                'subject' => 'Second subject',
                            ],
                        ],
                    ],
                ],
                ['firstEntry' => 'First title', 'secondEntry' => 'Second title'],
            ],
        ];
    }

    #[DataProvider('customNotificationsSettingsDataProvider')]
    #[Test]
    public function getCustomNotificationsTest(array $settings, array $expected): void
    {
        $result = $this->subject->getCustomNotifications($settings);
        self::assertEquals($expected, $result);
    }
}
