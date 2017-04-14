<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\SettingsService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SettingsServiceTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Service\SettingsService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Service\SettingsService();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Data provider for settings (clear cache uids)
     *
     * @return array
     */
    public function clearCacheSettingsDataProvider()
    {
        return [
            'emptySettings' => [
                [],
                []
            ],
            'settingsWithNoListAndDetail' => [
                [
                    'clearCacheUids' => 1
                ],
                [
                    '0' => 1
                ]
            ],
            'settingsWithListAndNoDetail' => [
                [
                    'clearCacheUids' => 1,
                    'listPid' => 2
                ],
                [
                    '0' => 1,
                    '1' => 2
                ]
            ],
            'settingsWithListAndDetail' => [
                [
                    'clearCacheUids' => 1,
                    'detailPid' => 3,
                    'listPid' => 2
                ],
                [
                    '0' => 1,
                    '1' => 3,
                    '2' => 2
                ]
            ],
            'multipleClearCacheUidsWithListAndDetail' => [
                [
                    'clearCacheUids' => '1,2,3',
                    'detailPid' => 5,
                    'listPid' => 4
                ],
                [
                    '0' => 1,
                    '1' => 2,
                    '2' => 3,
                    '3' => 5,
                    '4' => 4
                ]
            ],
            'wrongClearCacheUids' => [
                [
                    'clearCacheUids' => '1,,3',
                ],
                [
                    '0' => 1,
                    '1' => 3
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider clearCacheSettingsDataProvider
     */
    public function getClearCacheUids($settings, $expected)
    {
        $this->assertEquals($expected, $this->subject->getClearCacheUids($settings));
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
                []
            ],
            'oneEntry' => [
                [
                    'notification' => [
                        'customNotifications' => [
                            'firstEntry' => [
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject'
                            ]
                        ]
                    ]
                ],
                ['firstEntry' => 'First title']
            ],
            'twoEntry' => [
                [
                    'notification' => [
                        'customNotifications' => [
                            'firstEntry' => [
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject'
                            ],
                            'secondEntry' => [
                                'title' => 'Second title',
                                'template' => 'Second template',
                                'subject' => 'Second subject'
                            ]

                        ]
                    ]
                ],
                ['firstEntry' => 'First title', 'secondEntry' => 'Second title']
            ],
        ];
    }

    /**
     * @test
     * @dataProvider customNotificationsSettingsDataProvider
     */
    public function getCustomNotificationsTest($settings, $expected)
    {
        $result = $this->subject->getCustomNotifications($settings);
        $this->assertEquals($expected, $result);
    }
}
