<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Domain\Model\Dto;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class UserRegistrationDemandTest extends UnitTestCase
{
    protected UserRegistrationDemand $subject;

    protected function setUp(): void
    {
        $this->subject = new UserRegistrationDemand();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getDisplayModeReturnsInitialValue(): void
    {
        self::assertSame(
            'all',
            $this->subject->getDisplayMode()
        );
    }

    /**
     * @test
     */
    public function setDisplayModeForStringSetsDisplayMode(): void
    {
        $this->subject->setDisplayMode('past');
        self::assertEquals('past', $this->subject->getDisplayMode());
    }

    /**
     * @test
     */
    public function getStoragePageReturnsInitialValue(): void
    {
        self::assertEquals('', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function setStoragePageForStringSetsStoragePage(): void
    {
        $this->subject->setStoragePage('1,2,3');
        self::assertEquals('1,2,3', $this->subject->getStoragePage());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsDateTimeObjectIfNoValueSet(): void
    {
        self::assertInstanceOf('DateTime', $this->subject->getCurrentDateTime());
    }

    /**
     * @test
     */
    public function getCurrentDateTimeReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setCurrentDateTime(new DateTime('01.01.2014'));
        self::assertEquals(
            new DateTime('01.01.2014'),
            $this->subject->getCurrentDateTime()
        );
    }

    /**
     * @test
     */
    public function getOrderFieldReturnsEmptyStringIfNoValueSet(): void
    {
        self::assertSame(
            '',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     */
    public function getOrderFieldReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setOrderField('title');
        self::assertSame(
            'title',
            $this->subject->getOrderField()
        );
    }

    /**
     * @test
     */
    public function getOrderDirectionReturnsEmptyStringIfNoValueSet(): void
    {
        self::assertSame(
            '',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     */
    public function getOrderDirectionReturnsGivenValueIfValueSet(): void
    {
        $this->subject->setOrderDirection('asc');
        self::assertSame(
            'asc',
            $this->subject->getOrderDirection()
        );
    }

    /**
     * @test
     */
    public function getUserReturnsInitialValue(): void
    {
        self::assertNull($this->subject->getUser());
    }

    /**
     * @test
     */
    public function setUserSetsUser(): void
    {
        $user = new FrontendUser();
        $this->subject->setUser($user);
        self::assertSame($this->subject->getUser(), $user);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectIfEmptySettings(): void
    {
        $expected = new UserRegistrationDemand();
        $current = UserRegistrationDemand::createFromSettings();

        self::assertEquals($expected, $current);
    }

    /**
     * @test
     */
    public function createFromSettingsReturnsExpectedObjectWithSettings(): void
    {
        $expected = new UserRegistrationDemand();
        $expected->setDisplayMode('current');
        $expected->setOrderField('title');
        $expected->setOrderDirection('desc');
        $expected->setStoragePage('1,2,3');

        $settings = [
            'userRegistration' => [
                'displayMode' => 'current',
                'orderField' => 'title',
                'orderDirection' => 'desc',
                'storagePage' => '1,2,3',
                'recursive' => 0,
            ],
        ];

        $current = UserRegistrationDemand::createFromSettings($settings);

        self::assertEquals($expected, $current);
    }
}
