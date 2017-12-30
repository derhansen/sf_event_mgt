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

use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use DERHANSEN\SfEventMgt\Service\EmailService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\BeUserSessionService.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class BeUserSessionServiceTest extends UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Service\BeUserSessionService
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new BeUserSessionService();
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
     * @test
     */
    public function saveSessionDataSavesDataToSession()
    {
        $data = ['key' => 'value'];

        $mockBackendUser = $this->getMock(BackendUserAuthentication::class, ['setAndSaveSessionData'], [], '', false);
        $mockBackendUser->expects($this->once())->method('setAndSaveSessionData');
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->saveSessionData($data);
    }

    /**
     * @test
     */
    public function getSessionDataReturnsSessionData()
    {
        $mockBackendUser = $this->getMock(BackendUserAuthentication::class, ['getSessionData'], [], '', false);
        $mockBackendUser->expects($this->once())->method('getSessionData');
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
                    'key2' => 'value2'
                ],
                'key1',
                'value1'
            ],
            'key_not_found' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                'key3',
                null
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getSessionDataByKeyDataProvider
     */
    public function getSessionDataByKeyReturnsExpectedValue($sessionData, $key, $expected)
    {
        $mockBackendUser = $this->getMock(BackendUserAuthentication::class, ['getSessionData'], [], '', false);
        $mockBackendUser->expects($this->once())->method('getSessionData')->will($this->returnValue($sessionData));
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->assertEquals($expected, $this->subject->getSessionDataByKey($key));
    }
}
