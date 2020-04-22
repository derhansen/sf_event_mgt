<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Payment\Invoice;
use DERHANSEN\SfEventMgt\Payment\Transfer;
use DERHANSEN\SfEventMgt\Service\PaymentService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class PaymentServiceTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PaymentServiceTest extends UnitTestCase
{
    /**
     * @var PaymentService
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] = [
            'invoice' => [
                'class' => Invoice::class,
                'extkey' => 'sf_event_mgt'
            ],
            'transfer' => [
                'class' => Transfer::class,
                'extkey' => 'sf_event_mgt'
            ]
        ];
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * DataProvider for getPaymentMethodsReturnsDefaultPaymentMethods
     *
     * @return array
     */
    public function getPaymentMethodsDataProvider()
    {
        return [
            'Default Payment Methods enabled' => [
                'extConf' => [
                    'enableInvoice' => true,
                    'enableTransfer' => true
                ],
                'expected' => ['invoice' => null, 'transfer' => null]
            ],
            'Invoice disabled' => [
                'extConf' => [
                    'enableInvoice' => false,
                    'enableTransfer' => true
                ],
                'expected' => ['transfer' => null]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getPaymentMethodsDataProvider
     * @param mixed $extConf
     * @param mixed $expected
     */
    public function getPaymentMethodsReturnsDefaultPaymentMethods($extConf, $expected)
    {
        $this->subject = $this->getAccessibleMock(PaymentService::class, ['translate'], [], '', false);
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sf_event_mgt'] = $extConf;
        self::assertEquals($expected, $this->subject->getPaymentMethods());
    }

    /**
     * @test
     */
    public function getPaymentInstanceReturnsInvoicePaymentInsance()
    {
        $this->subject = new PaymentService();
        self::assertInstanceOf(Invoice::class, $this->subject->getPaymentInstance('invoice'));
    }

    /**
     * @test
     */
    public function getPaymentInstanceReturnsTransferPaymentInsance()
    {
        $this->subject = new PaymentService();
        self::assertInstanceOf(Transfer::class, $this->subject->getPaymentInstance('transfer'));
    }

    /**
     * Data provider for paymentActionEnabledForDefaultPaymentMethodReturnsExpectedResult
     *
     * @return array
     */
    public function paymentActionEnabledForDefaultPaymentMethodDataProvider()
    {
        return [
            'redirectAction' => [
                'redirectAction',
                false
            ],
            'successAction' => [
                'successAction',
                false
            ],
            'failureAction' => [
                'failureAction',
                false
            ],
            'cancelAction' => [
                'cancelAction',
                false
            ],
            'notifyAction' => [
                'notifyAction',
                false
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForDefaultPaymentMethodDataProvider
     * @param mixed $action
     * @param mixed $expected
     */
    public function paymentActionEnabledForDefaultPaymentMethodReturnsExpectedResult($action, $expected)
    {
        $this->subject = $this->getAccessibleMock(PaymentService::class, ['getPaymentInstance'], [], '', false);
        $this->subject->expects(self::once())->method('getPaymentInstance')->willReturn(new Invoice());

        self::assertEquals($expected, $this->subject->paymentActionEnabled('invoice', $action));
    }

    /**
     * Data provider for paymentActionEnabledForCustomPaymentMethodReturnsExpectedResult
     *
     * @return array
     */
    public function paymentActionEnabledForCustomPaymentMethodDataProvider()
    {
        return [
            'redirectAction' => [
                'redirectAction',
                true
            ],
            'successAction' => [
                'successAction',
                true
            ],
            'failureAction' => [
                'failureAction',
                true
            ],
            'cancelAction' => [
                'cancelAction',
                true
            ],
            'notifyAction' => [
                'notifyAction',
                true
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForCustomPaymentMethodDataProvider
     * @param mixed $action
     * @param mixed $expected
     */
    public function paymentActionEnabledForCustomPaymentMethodReturnsExpectedResult($action, $expected)
    {
        $mockPaymentInstance = $this->getAccessibleMock(
            Invoice::class,
            [
                'isRedirectEnabled',
                'isSuccessLinkEnabled',
                'isFailureLinkEnabled',
                'isCancelLinkEnabled',
                'isNotifyLinkEnabled',
            ],
            [],
            '',
            false
        );

        $mockPaymentInstance->expects(self::any())->method('isRedirectEnabled')->willReturn(true);
        $mockPaymentInstance->expects(self::any())->method('isSuccessLinkEnabled')->willReturn(true);
        $mockPaymentInstance->expects(self::any())->method('isFailureLinkEnabled')->willReturn(true);
        $mockPaymentInstance->expects(self::any())->method('isCancelLinkEnabled')->willReturn(true);
        $mockPaymentInstance->expects(self::any())->method('isNotifyLinkEnabled')->willReturn(true);

        $this->subject = $this->getAccessibleMock(PaymentService::class, ['getPaymentInstance'], [], '', false);
        $this->subject->expects(self::once())->method('getPaymentInstance')->willReturn(
            $mockPaymentInstance
        );

        self::assertEquals($expected, $this->subject->paymentActionEnabled('invoice', $action));
    }
}
