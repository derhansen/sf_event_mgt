<?php
declare(strict_types = 1);
namespace DERHANSEN\SfEventMgt\Event;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;

/**
 * This event is triggered before the payment success view is rendered. This event must be used to handle feedback
 * from the payment provider when payment has been successful.
 */
final class ProcessPaymentSuccessEvent
{
    /**
     * @var array
     */
    private $variables;

    /**
     * @var string
     */
    private $paymentMethod;

    /**
     * @var bool
     */
    private $updateRegistration;

    /**
     * @var Registration
     */
    private $registration;

    /**
     * @var array
     */
    private $getVariables;

    /**
     * @var PaymentController
     */
    private $paymentController;

    public function __construct(
        array $variables,
        string $paymentMethod,
        bool $updateRegistration,
        Registration $registration,
        array $getVariables,
        PaymentController $paymentController
    ) {
        $this->variables = $variables;
        $this->paymentMethod = $paymentMethod;
        $this->updateRegistration = $updateRegistration;
        $this->registration = $registration;
        $this->getVariables = $getVariables;
        $this->paymentController = $paymentController;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return bool
     */
    public function getUpdateRegistration(): bool
    {
        return $this->updateRegistration;
    }

    /**
     * @return Registration
     */
    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    /**
     * @return array
     */
    public function getGetVariables(): array
    {
        return $this->getVariables;
    }

    /**
     * @return PaymentController
     */
    public function getPaymentController(): PaymentController
    {
        return $this->paymentController;
    }

    /**
     * @param array $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    /**
     * @param bool $updateRegistration
     */
    public function setUpdateRegistration(bool $updateRegistration): void
    {
        $this->updateRegistration = $updateRegistration;
    }
}
