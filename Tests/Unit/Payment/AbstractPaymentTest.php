<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Payment;

use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class DERHANSEN\SfEventMgt\Payment\AbstractPayment.
 */
class AbstractPaymentTest extends UnitTestCase
{
    /**
     * @var AbstractPayment
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMockForAbstractClass(AbstractPayment::class);
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function isEnableRedirectReturnsDefaultValue()
    {
        self::assertFalse($this->subject->isRedirectEnabled());
    }

    /**
     * @test
     */
    public function isCancelLinkEnabledReturnsDefaultValue()
    {
        self::assertFalse($this->subject->isCancelLinkEnabled());
    }

    /**
     * @test
     */
    public function isNotifyLinkEnabledReturnsDefaultValue()
    {
        self::assertFalse($this->subject->isNotifyLinkEnabled());
    }

    /**
     * @test
     */
    public function isSuccessLinkEnabledReturnsDefaultValue()
    {
        self::assertFalse($this->subject->isSuccessLinkEnabled());
    }

    /**
     * @test
     */
    public function isFailureLinkEnabledReturnsDefaultValue()
    {
        self::assertFalse($this->subject->isFailureLinkEnabled());
    }
}
