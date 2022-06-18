<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\SpamChecks\AbstractSpamCheck;
use DERHANSEN\SfEventMgt\SpamChecks\Exceptions\SpamCheckNotFoundException;

/**
 * Service to process spam checks configured in TypoScript
 */
class SpamCheckService
{
    protected array $settings = [];
    protected array $arguments = [];
    protected Registration $registration;
    protected int $maxSpamScore = 10;
    protected int $checkScore = 0;

    /**
     * SpamCheckService constructor.
     *
     * @param Registration $registration
     * @param array $settings
     * @param array $arguments
     */
    public function __construct(Registration $registration, array $settings, array $arguments)
    {
        $this->registration = $registration;
        $this->settings = $settings;
        $this->arguments = $arguments;

        if (isset($settings['maxSpamScore'])) {
            $this->maxSpamScore = (int)$settings['maxSpamScore'];
        }

        if (!isset($settings['checks']) || empty($settings['checks'])) {
            $this->settings['checks'] = [];
        }
    }

    /**
     * Returns, if the spam check failed
     *
     * @throws SpamCheckNotFoundException
     * @return bool
     */
    public function isSpamCheckFailed(): bool
    {
        if ((bool)($this->settings['enabled'] ?? false)) {
            $this->processSpamChecks();
        }

        return $this->checkScore >= $this->maxSpamScore;
    }

    /**
     * Processes all configured spam checks
     *
     * @throws SpamCheckNotFoundException
     */
    protected function processSpamChecks(): void
    {
        foreach ($this->settings['checks'] ?? [] as $checkConfig) {
            if (!class_exists($checkConfig['class'] ?? '')) {
                throw new SpamCheckNotFoundException('Class ' . $checkConfig['class'] . ' does not exists');
            }
            $this->processSpamCheck($checkConfig);
        }
    }

    /**
     * Prococesses the spam check in the given config
     *
     * @param array $checkConfig
     */
    protected function processSpamCheck(array $checkConfig): void
    {
        if (!(bool)($checkConfig['enabled'] ?? false)) {
            return;
        }
        $configuration = $checkConfig['configuration'] ?? [];
        /** @var AbstractSpamCheck $spamCheck */
        $spamCheck = new $checkConfig['class'](
            $this->registration,
            $this->settings,
            $this->arguments,
            $configuration
        );
        if ($spamCheck->isFailed()) {
            $this->checkScore += (int)$checkConfig['increaseScore'];
        }
    }
}
