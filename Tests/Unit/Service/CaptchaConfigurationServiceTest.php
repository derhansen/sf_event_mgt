<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Exception\InvalidCaptchaConfigurationException;
use DERHANSEN\SfEventMgt\Service\CaptchaConfigurationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CaptchaConfigurationServiceTest extends UnitTestCase
{
    private const VALID_HCAPTCHA_CONFIG = [
        'enabled' => 1,
        'type' => 'hCaptcha',
        'hCaptcha' => [
            'apiScript' => 'https://hcaptcha-apiscript.tld/',
            'verificationServer' => 'https://hcaptcha-verificationserver.tld/',
            'publicKey' => 'publickey',
            'privateKey' => 'privatekey',
        ],
    ];
    private const VALID_RECAPTCHA_CONFIG = [
        'enabled' => 1,
        'type' => 'reCaptcha',
        'reCaptcha' => [
            'apiScript' => 'https://recaptcha-apiscript.tld/',
            'verificationServer' => 'https://recaptcha-verificationserver.tld/',
            'siteKey' => 'sitekey',
            'secretKey' => 'secretkey',
        ],
    ];

    #[Test]
    public function captchaConfigurationServiceIsNotEnabledForEmptySettings(): void
    {
        $service = new CaptchaConfigurationService();
        self::assertFalse($service->getEnabled());
    }

    public static function captchaConfigurationThrowsExceptionForInvalidSettingsDataProvider(): array
    {
        return [
            'emptyType' => [
                [
                    'enabled' => 1,
                    'type' => '',
                ],
                1631962901,
            ],
            'invalidType' => [
                [
                    'enabled' => 1,
                    'type' => 'foo',
                ],
                1631962901,
            ],
            'emptyApiScript' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => '',
                    ],
                ],
                1631962907,
            ],
            'invalidApiScript' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'no url',
                    ],
                ],
                1631962907,
            ],
            'emptyVerificationServer' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => '',
                    ],
                ],
                1631962990,
            ],
            'invalidVerificationServer' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => 'no url',
                    ],
                ],
                1631962990,
            ],
            'invalidPublicKey' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => 'https://verificationserver.tld/',
                        'publicKey' => '',
                    ],
                ],
                1631964323,
            ],
            'noPublicKey' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => 'https://verificationserver.tld/',
                    ],
                ],
                1631964323,
            ],
            'invalidPrivateKey' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => 'https://verificationserver.tld/',
                        'publicKey' => '1234567890',
                        'privateKey' => '',
                    ],
                ],
                1631964328,
            ],
            'noPrivateKey' => [
                [
                    'enabled' => 1,
                    'type' => 'hCaptcha',
                    'hCaptcha' => [
                        'apiScript' => 'https://apiserver.tld/',
                        'verificationServer' => 'https://verificationserver.tld/',
                        'publicKey' => '1234567890',
                    ],
                ],
                1631964328,
            ],
        ];
    }

    #[DataProvider('captchaConfigurationThrowsExceptionForInvalidSettingsDataProvider')]
    #[Test]
    public function captchaConfigurationThrowsExceptionForInvalidSettings(array $settings, int $code): void
    {
        $this->expectException(InvalidCaptchaConfigurationException::class);
        $this->expectExceptionCode($code);
        $service = new CaptchaConfigurationService($settings);
    }

    public static function gettersReturnExpectedResultDataProvider(): array
    {
        return [
            'captchaDisabled' => [
                [
                    'disabled' => 1,
                ],
                'getEnabled',
                false,
            ],
            'hCaptchaType' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getType',
                'hCaptcha',
            ],
            'hCaptchaApiScript' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getApiScript',
                'https://hcaptcha-apiscript.tld/',
            ],
            'hCaptchaVerificationServer' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getVerificationServer',
                'https://hcaptcha-verificationserver.tld/',
            ],
            'hCaptchaPublicKey' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getPublicKey',
                'publickey',
            ],
            'hCaptchaPrivateKey' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getPrivateKey',
                'privatekey',
            ],
            'hCaptchaResponseField' => [
                self::VALID_HCAPTCHA_CONFIG,
                'getResponseField',
                'h-captcha-response',
            ],
            'reCaptchaType' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getType',
                'reCaptcha',
            ],
            'reCaptchaApiScript' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getApiScript',
                'https://recaptcha-apiscript.tld/',
            ],
            'reCaptchaVerificationServer' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getVerificationServer',
                'https://recaptcha-verificationserver.tld/',
            ],
            'reCaptchaPublicKey' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getPublicKey',
                'sitekey',
            ],
            'reCaptchaPrivateKey' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getPrivateKey',
                'secretkey',
            ],
            'reCaptchaResponseField' => [
                self::VALID_RECAPTCHA_CONFIG,
                'getResponseField',
                'g-recaptcha-response',
            ],
        ];
    }

    #[DataProvider('gettersReturnExpectedResultDataProvider')]
    #[Test]
    public function gettersReturnExpectedResult(array $settings, string $method, $expected): void
    {
        $service = new CaptchaConfigurationService($settings);
        $result = $service->{$method}();
        self::assertEquals($expected, $result);
    }
}
