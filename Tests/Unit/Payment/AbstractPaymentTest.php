<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Payment;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Payment\AbstractPayment;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AbstractPaymentTest extends UnitTestCase
{
    protected AbstractPayment $subject;

    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMockForAbstractClass(AbstractPayment::class);
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function isEnableRedirectReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->isRedirectEnabled());
    }

    #[Test]
    public function isCancelLinkEnabledReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->isCancelLinkEnabled());
    }

    #[Test]
    public function isNotifyLinkEnabledReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->isNotifyLinkEnabled());
    }

    #[Test]
    public function isSuccessLinkEnabledReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->isSuccessLinkEnabled());
    }

    #[Test]
    public function isFailureLinkEnabledReturnsDefaultValue(): void
    {
        self::assertFalse($this->subject->isFailureLinkEnabled());
    }
}
