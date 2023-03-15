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
use Psr\Http\Message\ResponseInterface;

/**
 * This event is triggered before the payment redirect view is rendered. The given response object is the result
 * of the redirectAction. Event listeners can replace the response by a custom response (e.g. a `RedirectResponse`)
 */
final class ModifyPaymentRedirectResponseEvent
{
    private ResponseInterface $response;
    private array $settings;
    private array $variables;
    private Registration $registration;
    private PaymentController $paymentController;

    public function __construct(
        ResponseInterface $response,
        array $settings,
        array $variables,
        Registration $registration,
        PaymentController $paymentController
    ) {
        $this->response = $response;
        $this->settings = $settings;
        $this->variables = $variables;
        $this->registration = $registration;
        $this->paymentController = $paymentController;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }

    public function getPaymentController(): PaymentController
    {
        return $this->paymentController;
    }
}
