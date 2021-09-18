<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Exception\InvalidCaptchaConfigurationException;

/**
 * CaptchaConfiguration which supports hCaptcha and reCaptcha
 */
class CaptchaConfigurationService
{
    private const RESPONSE_FIELD = [
        'hCaptcha' => 'h-captcha-response',
        'reCaptcha' => 'g-recaptcha-response',
    ];
    private const PUBLIC_KEY_FIELD = [
        'hCaptcha' => 'publicKey',
        'reCaptcha' => 'siteKey',
    ];
    private const PRIVATE_KEY_FIELD = [
        'hCaptcha' => 'privateKey',
        'reCaptcha' => 'secretKey',
    ];

    protected bool $enabled = false;
    protected string $type = '';
    protected string $apiScript = '';
    protected string $verificationServer = '';
    protected string $publicKey = '';
    protected string $privateKey = '';
    protected string $responseField = '';

    public function __construct(array $captchaSettings = [])
    {
        $this->enabled = (bool)($captchaSettings['enabled'] ?? false);
        $this->type = $captchaSettings['type'] ?? '';
        $this->apiScript = $captchaSettings[$this->type]['apiScript'] ?? '';
        $this->verificationServer = $captchaSettings[$this->type]['verificationServer'] ?? '';
        $this->publicKey = $captchaSettings[$this->type][self::PUBLIC_KEY_FIELD[$this->type]] ?? '';
        $this->privateKey = $captchaSettings[$this->type][self::PRIVATE_KEY_FIELD[$this->type]] ?? '';
        $this->responseField = self::RESPONSE_FIELD[$this->type] ?? '';

        $this->validateSettings();
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getApiScript(): string
    {
        return $this->apiScript;
    }

    public function getVerificationServer(): string
    {
        return $this->verificationServer;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getResponseField(): string
    {
        return $this->responseField;
    }

    /**
     * Checks, if all properties contain valid values
     */
    private function validateSettings(): void
    {
        // If captcha is not enabled, there is no need to verify the settings
        if (!$this->enabled) {
            return;
        }

        if ($this->type === '' || !in_array($this->type, ['hCaptcha', 'reCaptcha'])) {
            throw new InvalidCaptchaConfigurationException(
                'Invalid captcha type settings. Valid values are "hCaptcha" and "reCaptcha',
                1631962901
            );
        }
        if ($this->apiScript === '' || !filter_var($this->apiScript, FILTER_VALIDATE_URL)) {
            throw new InvalidCaptchaConfigurationException(
                'Invalid apiScript setting.',
                1631962907
            );
        }
        if ($this->verificationServer === '' || !filter_var($this->verificationServer, FILTER_VALIDATE_URL)) {
            throw new InvalidCaptchaConfigurationException(
                'Invalid verificationServer setting.',
                1631962990
            );
        }
        if ($this->publicKey === '') {
            throw new InvalidCaptchaConfigurationException(
                'Invalid publicKey/siteKey setting.',
                1631964323
            );
        }
        if ($this->privateKey === '') {
            throw new InvalidCaptchaConfigurationException(
                'Invalid privateKey/secretKey setting.',
                1631964328
            );
        }
    }
}
