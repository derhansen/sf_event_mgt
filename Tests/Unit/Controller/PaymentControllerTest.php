<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\PaymentController;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentCancelEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentFailureEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentInitializeEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentNotifyEvent;
use DERHANSEN\SfEventMgt\Event\ProcessPaymentSuccessEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\PaymentController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentControllerTest extends UnitTestCase
{
    /**
     * @var \DERHANSEN\SfEventMgt\Controller\PaymentController
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            PaymentController::class,
            [
                'redirect',
                'validateHmacForAction',
                'proceedWithAction'
            ],
            [],
            '',
            false
        );
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if ProcessPaymentInitializeEvent is dispatched
     *
     * @test
     */
    public function redirectActionCallsBeforeRedirectSignal()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getPaymentmethod')->willReturn('paypal');

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'sfEventMgtSettings' => null,
            'successUrl' => null,
            'failureUrl' => null,
            'cancelUrl' => null,
            'notifyUrl' => null,
            'registration' => $mockRegistration,
            'html' => ''
        ];

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ProcessPaymentInitializeEvent($values, 'paypal', false, $mockRegistration, $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->redirectAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if ProcessPaymentSuccessEvent is dispatched
     *
     * @test
     */
    public function successActionCallsProcessSuccessSignal()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getPaymentmethod')->willReturn('paypal');

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ProcessPaymentSuccessEvent($values, 'paypal', false, $mockRegistration, [], $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->successAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if ProcessPaymentFailureEvent is dispatched
     *
     * @test
     */
    public function failureActionCallsProcessFailureSignal()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getPaymentmethod')->willReturn('paypal');

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ProcessPaymentFailureEvent($values, 'paypal', false, false, $mockRegistration, [], $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->failureAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if ProcessPaymentCancelEvent is dispatched
     *
     * @test
     */
    public function cancelActionCallsProcessCancelSignal()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getPaymentmethod')->willReturn('paypal');

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ProcessPaymentCancelEvent($values, 'paypal', false, false, $mockRegistration, [], $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->cancelAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if ProcessPaymentNotifyEvent is dispatched
     *
     * @test
     */
    public function notifyActionCallsProcessNotifySignal()
    {
        $mockRegistration = $this->getMockBuilder(Registration::class)->getMock();
        $mockRegistration->expects(self::once())->method('getPaymentmethod')->willReturn('paypal');

        $mockUriBuilder = $this->getMockBuilder(UriBuilder::class)
            ->setMethods(['setUseCacheHash', 'uriFor'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMockBuilder(HashService::class)->getMock();
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
            ->disableOriginalConstructor()->getMock();
        $eventDispatcher->expects(self::once())->method('dispatch')->with(
            new ProcessPaymentNotifyEvent($values, 'paypal', false, $mockRegistration, [], $this->subject)
        );
        $this->inject($this->subject, 'eventDispatcher', $eventDispatcher);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);

        $this->subject->notifyAction($mockRegistration, 'a-hmac');
    }
}
