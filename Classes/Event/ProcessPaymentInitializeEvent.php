<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Event;

use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered before the payment redirect view is rendered. This event must be used to process initialize
 * the payment process with the payment provider.
 */
final class ProcessPaymentInitializeEvent
{
    private array $variables;
    private string $paymentMethod;
    private bool $updateRegistration;
    private Registration $registration;
    private PaymentController $paymentController;

    public function __construct(
        array $variables,
        string $paymentMethod,
        bool $updateRegistration,
        Registration $registration,
        PaymentController $paymentController
    ) {
        $this->variables = $variables;
        $this->paymentMethod = $paymentMethod;
        $this->updateRegistration = $updateRegistration;
        $this->registration = $registration;
        $this->paymentController = $paymentController;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getUpdateRegistration(): bool
    {
        return $this->updateRegistration;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getPaymentController(): PaymentController
    {
        return $this->paymentController;
    }

    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    public function setUpdateRegistration(bool $updateRegistration): void
    {
        $this->updateRegistration = $updateRegistration;
    }
}
