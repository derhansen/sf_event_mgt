<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\BeUserSessionService.
 */
class BeUserSessionServiceTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Service\BeUserSessionService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new BeUserSessionService();
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function saveSessionDataSavesDataToSession()
    {
        $data = ['key' => 'value'];

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('setAndSaveSessionData');
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->saveSessionData($data);
    }

    /**
     * @test
     */
    public function getSessionDataReturnsSessionData()
    {
        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->onlyMethods(['getSessionData'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('getSessionData');
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->getSessionData();
    }

    /**
     * @return array
     */
    public function getSessionDataByKeyDataProvider()
    {
        return [
            'key_found' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                'key1',
                'value1',
            ],
            'key_not_found' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2',
                ],
                'key3',
                null,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getSessionDataByKeyDataProvider
     * @param mixed $sessionData
     * @param mixed $key
     * @param mixed $expected
     */
    public function getSessionDataByKeyReturnsExpectedValue($sessionData, $key, $expected)
    {
        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('getSessionData')->willReturn($sessionData);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        self::assertEquals($expected, $this->subject->getSessionDataByKey($key));
    }
}
