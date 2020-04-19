<?php

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
    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var Registration
     */
    protected $registration;

    /**
     * @var int
     */
    protected $maxSpamScore = 10;

    /**
     * @var int
     */
    protected $checkScore = 0;

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

        if (!isset($settings['checks']) || $settings['checks'] === null) {
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
        if ((bool)$this->settings['enabled']) {
            $this->processSpamChecks();
        }

        return $this->checkScore >= $this->maxSpamScore;
    }

    /**
     * Processes all configured spam checks
     *
     * @throws SpamCheckNotFoundException
     */
    protected function processSpamChecks()
    {
        foreach ($this->settings['checks'] as $checkConfig) {
            if (!class_exists($checkConfig['class'])) {
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
    protected function processSpamCheck(array $checkConfig)
    {
        if (!(bool)$checkConfig['enabled']) {
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
