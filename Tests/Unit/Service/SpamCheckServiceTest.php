<?php

declare(strict_types=1);

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

class SpamCheckServiceTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    /**
     * @test
     */
    public function spamCheckServiceWorksForEmptySettingsAndArguments(): void
    {
        $registration = new Registration();
        $settings = [];
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);
        self::assertFalse($service->isSpamCheckFailed());
    }

    public function isSpamCheckFailedReturnsFalseForExpectedConditionsDataProvider(): array
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
     */
    public function isSpamCheckFailedReturnsFalseForExpectedConditions(array $settings): void
    {
        $registration = new Registration();
        $arguments = [];
        $service = new SpamCheckService($registration, $settings, $arguments);
        self::assertFalse($service->isSpamCheckFailed());
    }

    /**
     * @test
     */
    public function spamCheckServiceThrowsExceptionWhenTestNotFound(): void
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
    public function maxSpamScoreIsSetInConstructor(): void
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
    public function emptyCheckArrayIsInitializedInConstructor(): void
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
    public function configuredSpamCheckIsProcessed(): void
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
