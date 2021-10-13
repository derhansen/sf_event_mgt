<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\SettingsService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\SettingsService.
 */
class SettingsServiceTest extends UnitTestCase
{
    /**
     * @var SettingsService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new SettingsService();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Data provider for settings (custom notifications)
     *
     * @return array
     */
    public function customNotificationsSettingsDataProvider()
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

    /**
     * @test
     * @dataProvider customNotificationsSettingsDataProvider
     * @param mixed $settings
     * @param mixed $expected
     */
    public function getCustomNotificationsTest($settings, $expected)
    {
        $result = $this->subject->getCustomNotifications($settings);
        self::assertEquals($expected, $result);
    }
}
