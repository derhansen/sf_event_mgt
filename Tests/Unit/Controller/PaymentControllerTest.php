<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

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

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Controller\PaymentController;

/**
 * Test case for class DERHANSEN\SfEventMgt\Controller\PaymentController.
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

    /**
     * @var \DERHANSEN\SfEventMgt\Controller\PaymentController
     */
    protected $subject = null;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            'DERHANSEN\\SfEventMgt\\Controller\\PaymentController',
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
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * Test if redirect signal gets emitted
     *
     * @test
     * @return void
     */
    public function redirectActionCallsBeforeRedirectSignal()
    {
        $mockRegistration = $this->getMock(Registration::class, [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock(UriBuilder::class, ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock(HashService::class, [], [], '', [], false);
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
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'redirectActionBeforeRedirectPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->redirectAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if success signal gets emitted
     *
     * @test
     * @return void
     */
    public function successActionCallsProcessSuccessSignal()
    {
        $mockRegistration = $this->getMock(Registration::class, [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock(UriBuilder::class, ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock(HashService::class, [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'successActionProcessSuccessPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->successAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if failure signal gets emitted
     *
     * @test
     * @return void
     */
    public function failureActionCallsProcessFailureSignal()
    {
        $mockRegistration = $this->getMock(Registration::class, [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock(UriBuilder::class, ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock(HashService::class, [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'failureActionProcessFailurePaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->failureAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if cancel signal gets emitted
     *
     * @test
     * @return void
     */
    public function cancelActionCallsProcessCancelSignal()
    {
        $mockRegistration = $this->getMock(Registration::class, [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock(UriBuilder::class, ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock(HashService::class, [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $removeRegistration = false;
        $arguments = [&$values, &$updateRegistration, &$removeRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'cancelActionProcessCancelPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->cancelAction($mockRegistration, 'a-hmac');
    }

    /**
     * Test if notify signal gets emitted
     *
     * @test
     * @return void
     */
    public function notifyActionCallsProcessNotifySignal()
    {
        $mockRegistration = $this->getMock(Registration::class, [], [], '', [], false);
        $mockRegistration->expects($this->once())->method('getPaymentmethod')->will($this->returnValue('paypal'));

        $mockUriBuilder = $this->getMock(UriBuilder::class, ['setUseCacheHash','uriFor'], [], '', [], false);
        $this->inject($this->subject, 'uriBuilder', $mockUriBuilder);

        $mockHashService = $this->getMock(HashService::class, [], [], '', [], false);
        $this->inject($this->subject, 'hashService', $mockHashService);

        $values = [
            'html' => ''
        ];
        $updateRegistration = false;
        $arguments = [&$values, &$updateRegistration, $mockRegistration, [], $this->subject];

        $mockedSignalSlotDispatcher = $this->getAccessibleMock(Dispatcher::class, ['dispatch']);
        $mockedSignalSlotDispatcher->expects($this->once())->method('dispatch')->with(
            PaymentController::class,
            'notifyActionProcessNotifyPaypal',
            $arguments
        );
        $this->subject->_set('signalSlotDispatcher', $mockedSignalSlotDispatcher);

        $view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
        $this->inject($this->subject, 'view', $view);

        $this->subject->notifyAction($mockRegistration, 'a-hmac');
    }

}
