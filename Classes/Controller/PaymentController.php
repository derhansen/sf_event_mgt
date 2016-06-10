<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use DERHANSEN\SfEventMgt\Payment\Exception\PaymentException;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use TYPO3\CMS\Extbase\Security\Exception\InvalidHashException;

/**
 * PaymentController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * PaymentService
     *
     * @var PaymentService
     */
    protected $paymentService;

    /**
     * Hash Service
     *
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     */
    protected $hashService;

    /**
     * Registration repository
     *
     * @var \DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository
     */
    protected $registrationRepository = null;

    /**
     * DI for paymentService
     *
     * @param PaymentService $paymentService
     */
    public function injectPaymentService(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * DI for hashService
     *
     * @param HashService $hashService
     */
    public function injectHashService(HashService $hashService)
    {
        $this->hashService = $hashService;
    }

    /**
     * DI for registrationRepository
     *
     * @param RegistrationRepository $registrationRepository
     */
    public function injectRegistrationRepository(RegistrationRepository $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
    }

    /**
     * Catches all PaymentExceptions and sets the Exception message to the response content
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        // @todo Catch Hash Exceptions
        try {
            parent::processRequest($request, $response);
        } catch (\DERHANSEN\SfEventMgt\Exception $e) {
            $response->setContent('<div class="payment-error">' . $e->getMessage() . '</div>');
        }
    }

    /**
     * Redirect to payment provider
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function redirectAction($registration, $hmac)
    {
        // @todo Validate hmac

        // @todo Check if action is enabled for payment method
        $this->processWithAction($registration, $this->actionMethodName);

        $values = [
            'sfEventMgtSettings' => $this->settings,
            'successUrl' => $this->getPaymentUriForAction('success', $registration),
            'failureUrl' => $this->getPaymentUriForAction('failure', $registration),
            'cancelUrl' => $this->getPaymentUriForAction('cancel', $registration),
            'notifyUrl' => $this->getPaymentUriForAction('notify', $registration),
            'registration' => $registration,
            'html' => ''
        ];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called BeforeRedirect method was successful and the registration can be updated
         */
        $updateRegistration = false;
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'BeforeRedirect' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function successAction($registration, $hmac)
    {
        // @todo Validate hmac

        // @todo Check if action is enabled for payment method

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method was successful and the registration can be updated
         */
        $updateRegistration = false;
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessSuccess' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function failureAction($registration, $hmac)
    {
        // @todo Validate hmac

        // @todo Check if action is enabled for payment method

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method was successful and the registration can be updated
         */
        $updateRegistration = false;
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessFailure' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function cancelAction($registration, $hmac)
    {
        // @todo Validate hmac

        // @todo Check if action is enabled for payment method

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method was successful and the registration can be updated
         */
        $updateRegistration = false;
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessCancel' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @param string $hmac
     */
    public function notifyAction($registration, $hmac)
    {
        // @todo Validate hmac

        $values = ['html' => ''];

        $paymentMethod = $registration->getPaymentmethod();

        /**
         * If true, the externally called ProcessSuccess method was successful and the registration can be updated
         */
        $updateRegistration = false;
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            __FUNCTION__ . 'ProcessNotify' . ucfirst($paymentMethod),
            [&$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this]
        );

        if ($updateRegistration) {
            $this->registrationRepository->update($registration);
        }

        $this->view->assign('result', $values);
    }

    /**
     * Checks if the given action may be called for the given registration / event
     *
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration$registration
     * @param string $actionName
     * @throws PaymentException
     * @return void
     */
    protected function processWithAction($registration, $actionName)
    {
        // @todo - Should do the following:
        // 1. Check, if payment is enabled for Event
        // 2. Check, if payment method is enabled for Event
        // 3. Check, if action is configured for payment method
        // 4. Check, if "paid" is false for registration
        // If a condition does not match, throw a PaymentException
        // @todo - move to service
    }

    /**
     * Checks the HMAC for the given action and registration
     *
     * @param $registration
     * @param $hmac
     * @param $action
     * @throws InvalidHashException
     */
    protected function validateHmacForAction($registration, $hmac, $action)
    {
        // Should throw an InvalidHmac exception
        // @todo - move to service
    }

    /**
     * Returns the payment Uri for the given action and registration
     *
     * @param string $action
     * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
     * @return string
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException
     */
    protected function getPaymentUriForAction($action, $registration)
    {
        $this->uriBuilder
            ->setCreateAbsoluteUri(true)
            ->setUseCacheHash(false);
        return $this->uriBuilder->uriFor(
            $action,
            [
                'registration' => $registration,
                'hmac' => $this->hashService->generateHmac($action . '-' . $registration->getUid())
            ],
            'Payment',
            'sfeventmgt',
            'Pipayment'
        );
    }

}

