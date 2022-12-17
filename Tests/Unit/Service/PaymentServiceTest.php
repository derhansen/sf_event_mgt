<?php

declare(strict_types=1);

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

class PaymentServiceTest extends UnitTestCase
{
    protected PaymentService $subject;

    protected function setUp(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods'] = [
            'invoice' => [
                'class' => Invoice::class,
                'extkey' => 'sf_event_mgt',
            ],
            'transfer' => [
                'class' => Transfer::class,
                'extkey' => 'sf_event_mgt',
            ],
        ];
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    public function getPaymentMethodsDataProvider(): array
    {
        return [
            'Default Payment Methods enabled' => [
                'extConf' => [
                    'enableInvoice' => true,
                    'enableTransfer' => true,
                ],
                'expected' => ['invoice' => null, 'transfer' => null],
            ],
            'Invoice disabled' => [
                'extConf' => [
                    'enableInvoice' => false,
                    'enableTransfer' => true,
                ],
                'expected' => ['transfer' => null],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getPaymentMethodsDataProvider
     */
    public function getPaymentMethodsReturnsDefaultPaymentMethods(array $extConf, array $expected): void
    {
        $this->subject = $this->getAccessibleMock(PaymentService::class, ['translate'], [], '', false);
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['sf_event_mgt'] = $extConf;
        self::assertEquals($expected, $this->subject->getPaymentMethods());
    }

    /**
     * @test
     */
    public function getPaymentInstanceReturnsInvoicePaymentInsance(): void
    {
        $this->subject = new PaymentService();
        self::assertInstanceOf(Invoice::class, $this->subject->getPaymentInstance('invoice'));
    }

    /**
     * @test
     */
    public function getPaymentInstanceReturnsTransferPaymentInsance(): void
    {
        $this->subject = new PaymentService();
        self::assertInstanceOf(Transfer::class, $this->subject->getPaymentInstance('transfer'));
    }

    public function paymentActionEnabledForDefaultPaymentMethodDataProvider(): array
    {
        return [
            'redirectAction' => [
                'redirectAction',
                false,
            ],
            'successAction' => [
                'successAction',
                false,
            ],
            'failureAction' => [
                'failureAction',
                false,
            ],
            'cancelAction' => [
                'cancelAction',
                false,
            ],
            'notifyAction' => [
                'notifyAction',
                false,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForDefaultPaymentMethodDataProvider
     */
    public function paymentActionEnabledForDefaultPaymentMethodReturnsExpectedResult(string $action, bool $expected): void
    {
        $this->subject = $this->getAccessibleMock(PaymentService::class, ['getPaymentInstance'], [], '', false);
        $this->subject->expects(self::once())->method('getPaymentInstance')->willReturn(new Invoice());

        self::assertEquals($expected, $this->subject->paymentActionEnabled('invoice', $action));
    }

    public function paymentActionEnabledForCustomPaymentMethodDataProvider(): array
    {
        return [
            'redirectAction' => [
                'redirectAction',
                true,
            ],
            'successAction' => [
                'successAction',
                true,
            ],
            'failureAction' => [
                'failureAction',
                true,
            ],
            'cancelAction' => [
                'cancelAction',
                true,
            ],
            'notifyAction' => [
                'notifyAction',
                true,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider paymentActionEnabledForCustomPaymentMethodDataProvider
     */
    public function paymentActionEnabledForCustomPaymentMethodReturnsExpectedResult(string $action, bool $expected): void
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
