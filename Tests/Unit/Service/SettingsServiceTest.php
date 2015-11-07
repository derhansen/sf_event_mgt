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

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\SettingsService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SettingsServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
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
        return array(
            'emptySettings' => array(
                array(),
                array()
            ),
            'settingsWithNoListAndDetail' => array(
                array(
                    'clearCacheUids' => 1
                ),
                array(
                    '0' => 1
                )
            ),
            'settingsWithListAndNoDetail' => array(
                array(
                    'clearCacheUids' => 1,
                    'listPid' => 2
                ),
                array(
                    '0' => 1,
                    '1' => 2
                )
            ),
            'settingsWithListAndDetail' => array(
                array(
                    'clearCacheUids' => 1,
                    'detailPid' => 3,
                    'listPid' => 2
                ),
                array(
                    '0' => 1,
                    '1' => 3,
                    '2' => 2
                )
            ),
            'multipleClearCacheUidsWithListAndDetail' => array(
                array(
                    'clearCacheUids' => '1,2,3',
                    'detailPid' => 5,
                    'listPid' => 4
                ),
                array(
                    '0' => 1,
                    '1' => 2,
                    '2' => 3,
                    '3' => 5,
                    '4' => 4
                )
            ),
            'wrongClearCacheUids' => array(
                array(
                    'clearCacheUids' => '1,,3',
                ),
                array(
                    '0' => 1,
                    '1' => 3
                )
            ),
        );
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
        return array(
            'emptySettings' => array(
                array(),
                array()
            ),
            'oneEntry' => array(
                array(
                    'notification' => array(
                        'customNotifications' => array(
                            'firstEntry' => array(
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject'
                            )
                        )
                    )
                ),
                array('firstEntry' => 'First title')
            ),
            'twoEntry' => array(
                array(
                    'notification' => array(
                        'customNotifications' => array(
                            'firstEntry' => array(
                                'title' => 'First title',
                                'template' => 'First template',
                                'subject' => 'First subject'
                            ),
                            'secondEntry' => array(
                                'title' => 'Second title',
                                'template' => 'Second template',
                                'subject' => 'Second subject'
                            )

                        )
                    )
                ),
                array('firstEntry' => 'First title', 'secondEntry' => 'Second title')
            ),
        );
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
