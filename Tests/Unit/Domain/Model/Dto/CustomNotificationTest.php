<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use PHPUnit\Framework\Attributes\Test;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class CustomNotificationTest extends UnitTestCase
{
    protected CustomNotification $subject;

    protected function setUp(): void
    {
        $this->subject = new CustomNotification();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getTemplateReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getTemplate());
    }

    #[Test]
    public function setTemplateSetsValueForString(): void
    {
        $this->subject->setTemplate('a-template');
        self::assertEquals('a-template', $this->subject->getTemplate());
    }

    #[Test]
    public function getRecipientsReturnsInitialValue(): void
    {
        self::assertEquals(CustomNotification::RECIPIENTS_CONFIRMED, $this->subject->getRecipients());
    }

    #[Test]
    public function setRecipientsSetsValueForInteger(): void
    {
        $this->subject->setRecipients(CustomNotification::RECIPIENTS_ALL);
        self::assertEquals(CustomNotification::RECIPIENTS_ALL, $this->subject->getRecipients());
    }

    #[Test]
    public function getOverwriteSubjectReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getOverwriteSubject());
    }

    #[Test]
    public function setOverwriteSubjectSetsValueForString(): void
    {
        $this->subject->setOverwriteSubject('subject');
        self::assertEquals('subject', $this->subject->getOverwriteSubject());
    }

    #[Test]
    public function getAdditionalMessageReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getAdditionalMessage());
    }

    #[Test]
    public function setAdditionalMessageSetsValueForString(): void
    {
        $this->subject->setAdditionalMessage('message');
        self::assertEquals('message', $this->subject->getAdditionalMessage());
    }
}
