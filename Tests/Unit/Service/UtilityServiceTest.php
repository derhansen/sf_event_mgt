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

use DERHANSEN\SfEventMgt\Service\SettingsService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Service\CacheService;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\UtilityService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UtilityServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\UtilityService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new \DERHANSEN\SfEventMgt\Service\UtilityService();
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
     * Test for clearCacheForConfiguredUids with empty settings
     *
     * @test
     * @return void
     */
    public function clearCacheForConfiguredUidsWithEmptySettingsTest()
    {
        $settingsService = $this->getMockBuilder(SettingsService::class)->disableOriginalConstructor()->getMock();
        $settingsService->expects($this->once())->method('getClearCacheUids')->with([])->will($this->returnValue([]));
        $this->inject($this->subject, 'settingsService', $settingsService);
        $this->subject->clearCacheForConfiguredUids([]);
    }

    /**
     * Test for clearCacheForConfiguredUids with empty settings
     *
     * @test
     * @return void
     */
    public function clearCacheForConfiguredUidsWithSettingsTest()
    {
        $settings = ['clearCacheUids' => '1,2,3,4'];
        $settingsService = $this->getMockBuilder(SettingsService::class)->disableOriginalConstructor()->getMock();
        $settingsService->expects($this->once())->method('getClearCacheUids')->with($settings)->
        will($this->returnValue([1, 2, 3, 4]));
        $this->inject($this->subject, 'settingsService', $settingsService);

        $cacheService = $this->getMockBuilder(CacheService::class)->disableOriginalConstructor()->getMock();
        $cacheService->expects($this->once())->method('clearPageCache')->with([1, 2, 3, 4]);
        $this->inject($this->subject, 'cacheService', $cacheService);

        $this->subject->clearCacheForConfiguredUids($settings);
    }
}
