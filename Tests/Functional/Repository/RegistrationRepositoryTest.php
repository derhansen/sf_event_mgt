<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Repository;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\CustomNotification;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\FrontendUserRepository;
use DERHANSEN\SfEventMgt\Domain\Repository\RegistrationRepository;
use InvalidArgumentException;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class RegistrationRepositoryTest extends FunctionalTestCase
{
    protected RegistrationRepository $registrationRepository;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    /**
     * Setup
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->registrationRepository = $this->getContainer()->get(RegistrationRepository::class);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/registrations_confirmed_unconfirmed.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/registrations_for_notification.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/registrations_user_registrations.csv');
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/registrations_waitlist.csv');

        $request = (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $GLOBALS['TYPO3_REQUEST'] = $request;
    }

    /**
     * Test for match on Event
     *
     * @test
     */
    public function findNotificationRegistrationsForEventUid2(): void
    {
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(2);
        $customNotification = $this->getMockBuilder(CustomNotification::class)->getMock();
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, $customNotification, []);
        self::assertEquals(1, $registrations->count());
    }

    public function confirmedAndUnconfirmedDataProvider(): array
    {
        return [
            'all registrations' => [
                0,
                3,
            ],
            'confirmed' => [
                1,
                2,
            ],
            'unconfirmed' => [
                2,
                1,
            ],
        ];
    }

    /**
     * @dataProvider confirmedAndUnconfirmedDataProvider
     * @test
     */
    public function findNotificationRegistrationsReturnsConfirmedAndUnconfirmed($recipientSetting, $expected): void
    {
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(20);
        $customNotification = $this->getMockBuilder(CustomNotification::class)->getMock();
        $customNotification->expects(self::any())->method('getRecipients')->willReturn($recipientSetting);
        $registrations = $this->registrationRepository->findNotificationRegistrations($event, $customNotification, []);
        self::assertSame($expected, $registrations->count());
    }

    public function findNotificationRegistrationsDataProvider(): array
    {
        return [
            'withEmptyConstraints' => [
                [],
                3,
            ],
            'allPaidEquals1' => [
                [
                    'paid' => ['equals' => '1'],
                ],
                2,
            ],
            'confirmationUntilLessThan' => [
                [
                    'confirmationUntil' => ['lessThan' => '1402743600'],
                ],
                2,
            ],
            'confirmationUntilLessThanOrEqual' => [
                [
                    'confirmationUntil' => ['lessThanOrEqual' => '1402743600'],
                ],
                3,
            ],
            'confirmationUntilGreaterThan' => [
                [
                    'confirmationUntil' => ['greaterThan' => '1402740000'],
                ],
                1,
            ],
            'confirmationUntilGreaterThanOrEqual' => [
                [
                    'confirmationUntil' => ['greaterThanOrEqual' => '1402740000'],
                ],
                3,
            ],
            'multipleContraints' => [
                [
                    'confirmationUntil' => ['lessThan' => '1402743600'],
                    'paid' => ['equals' => '0'],
                ],
                1,
            ],
        ];
    }

    /**
     * Test for match on Event
     *
     * @dataProvider findNotificationRegistrationsDataProvider
     * @test
     * @param mixed $constraints
     * @param mixed $expected
     */
    public function findNotificationRegistrationsForEventUid1WithConstraints($constraints, $expected): void
    {
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(1);

        $customNotification = $this->getMockBuilder(CustomNotification::class)->getMock();

        $registrations = $this->registrationRepository->findNotificationRegistrations(
            $event,
            $customNotification,
            $constraints
        );
        self::assertEquals($expected, $registrations->count());
    }

    /**
     * Test for match on Event with unknown condition
     *
     * @test
     */
    public function findNotificationRegistrationsForEventWithConstraintsButWrongCondition(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $constraints = ['confirmationUntil' => ['wrongcondition' => '0']];
        $event = $this->getMockBuilder(Event::class)->getMock();
        $customNotification = $this->getMockBuilder(CustomNotification::class)->getMock();
        $this->registrationRepository->findNotificationRegistrations($event, $customNotification, $constraints);
    }

    /**
     * Test if ignoreNotifications is respected
     *
     * @test
     */
    public function findNotificationRegistrationsRespectsIgnoreNotificationsForEventUid3(): void
    {
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(3);
        $customNotification = $this->getMockBuilder(CustomNotification::class)->getMock();
        $registrations = $this->registrationRepository->findNotificationRegistrations(
            $event,
            $customNotification,
            []
        );
        self::assertEquals(1, $registrations->count());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand returns an empty array if no user given
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandReturnsEmptyArrayIfNoUser(): void
    {
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('all');
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals([], $registrations);
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects storagePage constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsStoragePage(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(1);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('all');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(3, $registrations->count());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects displayMode "future" constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsDisplaymodeFuture(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(1);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('future');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $demand->setCurrentDateTime(new \DateTime('01.06.2014 20:00'));
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(1, $registrations->count());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects displayMode "future" constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsDisplaymodeCurrentFuture(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(1);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('current_future');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $demand->setCurrentDateTime(new \DateTime('01.06.2014 10:00'));
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(2, $registrations->count());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects user constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsUser(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(2);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('all');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(1, $registrations->count());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects order constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsOrder(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(1);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('all');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $demand->setOrderField('event.startdate');
        $demand->setOrderDirection('asc');
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(30, $registrations->getFirst()->getUid());
    }

    /**
     * Test if findRegistrationsByUserRegistrationDemand respects order direction constraint
     *
     * @test
     */
    public function findRegistrationsByUserRegistrationDemandRespectsOrderDirection(): void
    {
        $feUserRepository = $this->getContainer()->get(FrontendUserRepository::class);
        $feUser = $feUserRepository->findByUid(1);
        $demand = new UserRegistrationDemand();
        $demand->setDisplayMode('all');
        $demand->setStoragePage('7');
        $demand->setUser($feUser);
        $demand->setOrderField('event.startdate');
        $demand->setOrderDirection('desc');
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        self::assertEquals(32, $registrations->getFirst()->getUid());
    }

    /**
     * @test
     */
    public function findWaitlistMoveUpRegistrationsReturnsExpectedAmountOfRegistrationsAndRespectsOrder(): void
    {
        $event = $this->getMockBuilder(Event::class)->getMock();
        $event->expects(self::once())->method('getUid')->willReturn(30);

        $registrations = $this->registrationRepository->findWaitlistMoveUpRegistrations($event);
        self::assertSame(2, $registrations->count());

        // Event with UID 50 should be first, since registration_date is ealier than registration UID 51
        self::assertSame(50, $registrations->getFirst()->getUid());
    }
}
