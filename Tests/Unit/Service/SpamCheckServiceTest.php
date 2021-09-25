<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\SpamCheckService;
use DERHANSEN\SfEventMgt\SpamChecks\Exceptions\SpamCheckNotFoundException;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Service\SpamCheckServiceTest.
 */
class SpamCheckServiceTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->resetSingletonInstances = true;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function spamCheckServiceWorksForEmptySettingsAndArguments()
    {
        $registration = new Registration();
        $settings = [];
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);
        self::assertFalse($service->isSpamCheckFailed());
    }

    /**
     * DataProvider for isSpamCheckFailedReturnsFalseForExpectedConditions
     */
    public function isSpamCheckFailedReturnsFalseForExpectedConditionsDataProvider()
    {
        return [
            'empty settings' => [
                [],
            ],
            'spamcheck disabled' => [
                [
                    'enabled' => 0,
                ],
            ],
            'spamcheck enabled but no checks' => [
                [
                    'enabled' => 1,
                    'checks' => [],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isSpamCheckFailedReturnsFalseForExpectedConditionsDataProvider
     * @param array $settings
     */
    public function isSpamCheckFailedReturnsFalseForExpectedConditions($settings)
    {
        $registration = new Registration();
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);
        self::assertFalse($service->isSpamCheckFailed());
    }

    /**
     * @test
     */
    public function spamCheckServiceThrowsExceptionWhenTestNotFound()
    {
        $this->expectException(SpamCheckNotFoundException::class);
        $registration = new Registration();
        $settings = [
            'enabled' => 1,
            'checks' => [
                0 => [
                    'class' => 'DERHANSEN\SfEventMgt\SpamChecks\FooCheck',
                ],
            ],
        ];
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);
        $service->isSpamCheckFailed();
    }

    /**
     * @test
     */
    public function maxSpamScoreIsSetInConstructor()
    {
        $registration = new Registration();
        $settings = [
            'enabled' => 1,
            'maxSpamScore' => 20,
        ];
        $arguments = [];

        $service = $this->getAccessibleMock(SpamCheckService::class, ['dummy'], [$registration, $settings, $arguments]);
        self::assertEquals(20, $service->_get('maxSpamScore'));
    }

    /**
     * @test
     */
    public function emptyCheckArrayIsInitializedInConstructor()
    {
        $registration = new Registration();
        $settings = [
            'enabled' => 1,
        ];
        $arguments = [];

        $service = $this->getAccessibleMock(SpamCheckService::class, ['dummy'], [$registration, $settings, $arguments]);
        self::assertEquals([], $service->_get('settings')['checks']);
    }

    /**
     * @test
     */
    public function configuredSpamCheckIsProcessed()
    {
        $registration = new Registration();
        $settings = [
            'enabled' => 1,
            'checks' => [
                0 => [
                    'enabled' => 1,
                    'class' => 'DERHANSEN\SfEventMgt\SpamChecks\HoneypotSpamCheck',
                    'increaseScore' => 10,
                ],
            ],
        ];
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);

        // Spam check should fail, since arguments do not include the honeypot field
        self::assertTrue($service->isSpamCheckFailed());
    }
}
