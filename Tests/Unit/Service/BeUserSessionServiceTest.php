<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\BeUserSessionService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class BeUserSessionServiceTest extends UnitTestCase
{
    protected BeUserSessionService $subject;

    protected function setUp(): void
    {
        $this->subject = new BeUserSessionService();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function saveSessionDataSavesDataToSession(): void
    {
        $data = ['key' => 'value'];

        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('setAndSaveSessionData');
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->saveSessionData($data);
    }

    #[Test]
    public function getSessionDataReturnsSessionData(): void
    {
        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->onlyMethods(['getSessionData'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('getSessionData');
        $GLOBALS['BE_USER'] = $mockBackendUser;

        $this->subject->getSessionData();
    }

    public static function getSessionDataByKeyDataProvider(): array
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

    #[DataProvider('getSessionDataByKeyDataProvider')]
    #[Test]
    public function getSessionDataByKeyReturnsExpectedValue(array $sessionData, string $key, ?string $expected): void
    {
        $mockBackendUser = $this->getMockBuilder(BackendUserAuthentication::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockBackendUser->expects(self::once())->method('getSessionData')->willReturn($sessionData);
        $GLOBALS['BE_USER'] = $mockBackendUser;

        self::assertEquals($expected, $this->subject->getSessionDataByKey($key));
    }
}
