<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case for class \DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotificationTest
 */
class CustomNotificationTest extends UnitTestCase
{
    /**
     * @var CustomNotification
     */
    protected $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new CustomNotification();
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
    public function getTemplateReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getTemplate());
    }

    /**
     * @test
     */
    public function setTemplateSetsValueForString()
    {
        $this->subject->setTemplate('a-template');
        self::assertEquals('a-template', $this->subject->getTemplate());
    }

    /**
     * @test
     */
    public function getRecipientsReturnsInitialValue()
    {
        self::assertEquals(CustomNotification::RECIPIENTS_CONFIRMED, $this->subject->getRecipients());
    }

    /**
     * @test
     */
    public function setRecipientsSetsValueForInteger()
    {
        $this->subject->setRecipients(CustomNotification::RECIPIENTS_ALL);
        self::assertEquals(CustomNotification::RECIPIENTS_ALL, $this->subject->getRecipients());
    }

    /**
     * @test
     */
    public function getOverwriteSubjectReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getOverwriteSubject());
    }

    /**
     * @test
     */
    public function setOverwriteSubjectSetsValueForString()
    {
        $this->subject->setOverwriteSubject('subject');
        self::assertEquals('subject', $this->subject->getOverwriteSubject());
    }

    /**
     * @test
     */
    public function getAdditionalMessageReturnsInitialValue()
    {
        self::assertEquals('', $this->subject->getAdditionalMessage());
    }

    /**
     * @test
     */
    public function setAdditionalMessageSetsValueForString()
    {
        $this->subject->setAdditionalMessage('message');
        self::assertEquals('message', $this->subject->getAdditionalMessage());
    }
}
