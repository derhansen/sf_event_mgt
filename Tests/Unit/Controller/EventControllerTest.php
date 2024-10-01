<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Controller;

use DERHANSEN\SfEventMgt\Controller\EventController;
use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Mvc\Controller\Argument;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Mvc\Controller\MvcPropertyMappingConfiguration;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class EventControllerTest extends UnitTestCase
{
    protected EventController&MockObject $subject;
    protected TypoScriptFrontendController&MockObject $tsfe;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = $this->getAccessibleMock(
            EventController::class,
            [
                'redirect',
                'addFlashMessage',
                'overwriteEventDemandObject',
                'persistAll',
                'htmlResponse',
            ],
            [],
            '',
            false
        );
        $this->tsfe = $this->getAccessibleMock(
            TypoScriptFrontendController::class,
            [],
            [],
            '',
            false
        );
        $GLOBALS['TSFE'] = $this->tsfe;
    }

    /**
     * Teardown
     */
    protected function tearDown(): void
    {
        unset($this->subject);
    }

    /**
     * Test if overwriteDemand ignores properties in $ignoredSettingsForOverwriteDemand
     */
    #[Test]
    public function overwriteDemandObjectIgnoresIgnoredProperties(): void
    {
        $demand = new EventDemand();
        $overwriteDemand = ['storagePage' => 1, 'category' => 1];

        $mockController = $this->getAccessibleMock(
            EventController::class,
            ['redirect', 'addFlashMessage'],
            [],
            '',
            false
        );
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        self::assertEmpty($resultDemand->getStoragePage());
    }

    /**
     * Test if overwriteDemand sets a property in the given demand
     */
    #[Test]
    public function overwriteDemandObjectSetsCategoryProperty(): void
    {
        $demand = new EventDemand();
        $overwriteDemand = ['category' => 1];

        $mockController = $this->getAccessibleMock(
            EventController::class,
            ['redirect', 'addFlashMessage'],
            [],
            '',
            false
        );
        $resultDemand = $mockController->_call('overwriteEventDemandObject', $demand, $overwriteDemand);
        self::assertSame('1', $resultDemand->getCategory());
    }

    #[Test]
    public function initializeSaveRegistrationActionSetsDateFormat(): void
    {
        $settings = [
            'registration' => [
                'formatDateOfBirth' => 'd.m.Y',
            ],
        ];

        $mockPropertyMapperConfig = $this->getMockBuilder(MvcPropertyMappingConfiguration::class)->getMock();
        $mockPropertyMapperConfig->expects(self::any())->method('setTypeConverterOption')->with(
            self::equalTo(DateTimeConverter::class),
            self::equalTo('dateFormat'),
            self::equalTo('d.m.Y')
        );

        $mockDateOfBirthPmConfig = $this->getMockBuilder(MvcPropertyMappingConfiguration::class)->getMock();
        $mockDateOfBirthPmConfig->expects(self::once())->method('forProperty')->with('dateOfBirth')->willReturn(
            $mockPropertyMapperConfig
        );

        $mockRegistrationArgument = $this->getMockBuilder(Argument::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockRegistrationArgument->expects(self::once())->method('getPropertyMappingConfiguration')->willReturn(
            $mockDateOfBirthPmConfig
        );

        $mockArguments = $this->getMockBuilder(Arguments::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockArguments->expects(self::any())->method('getArgument')->with('registration')->willReturn(
            $mockRegistrationArgument
        );

        $mockRequest = $this->createMock(Request::class);
        $mockRequest->expects(self::any())->method('getArguments')->willReturn([]);
        $mockRequest->expects(self::any())->method('getMethod')->willReturn('POST');

        $this->subject->_set('request', $mockRequest);
        $this->subject->_set('arguments', $mockArguments);
        $this->subject->_set('settings', $settings);
        $this->subject->initializeSaveRegistrationAction();
    }

    #[Test]
    public function handleEventNotFoundIsSkippedWhenNoSetting(): void
    {
        $settings = [
            'event' => [
                'errorHandling' => '',
            ],
        ];

        $this->expectExceptionCode(1671205677);
        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    #[Test]
    public function handleEventNotFoundRedirectsToListView(): void
    {
        $settings = [
            'listPid' => 100,
            'event' => [
                'errorHandling' => 'redirectToListView',
            ],
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->expects(self::once())->method('redirect')->with('list', null, null, null, 100);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    #[Test]
    public function handleEventNotFoundRedirectsToPid1IfNoListPidDefinied(): void
    {
        $settings = [
            'event' => [
                'errorHandling' => 'redirectToListView',
            ],
        ];

        $mock = $this->getAccessibleMock(EventController::class, ['redirect']);
        $mock->expects(self::once())->method('redirect')->with('list', null, null, null, 1);
        $mock->_call('handleEventNotFoundError', $settings);
    }

    public static function isOverwriteDemandDataProvider(): array
    {
        return [
            'setting is "1" - no overwriteDemand' => [
                ['disableOverrideDemand' => '1'],
                [],
                false,
            ],
            'setting is "1" - with overwriteDemand' => [
                ['disableOverrideDemand' => '1'],
                ['foo' => 'bar'],
                false,
            ],
            'setting is "0" - no overwriteDemand' => [
                ['disableOverrideDemand' => '0'],
                [],
                false,
            ],
            'setting is "0" - with overwriteDemand' => [
                ['disableOverrideDemand' => '0'],
                ['foo' => 'bar'],
                true,
            ],
            'setting is 1 - no overwriteDemand' => [
                ['disableOverrideDemand' => 1],
                [],
                false,
            ],
            'setting is 1 - with overwriteDemand' => [
                ['disableOverrideDemand' => 1],
                ['foo' => 'bar'],
                false,
            ],
            'setting is 0 - no overwriteDemand' => [
                ['disableOverrideDemand' => 0],
                [],
                false,
            ],
            'setting is 0 - with overwriteDemand' => [
                ['disableOverrideDemand' => 0],
                ['foo' => 'bar'],
                true,
            ],
        ];
    }

    #[DataProvider('isOverwriteDemandDataProvider')]
    #[Test]
    public function isOverwriteDemandIsWorking(array $settings, array $overwriteDemand, bool $expected): void
    {
        $mockedController = $this->getAccessibleMock(EventController::class, null);

        $mockedController->_set('settings', $settings);
        self::assertEquals($expected, $mockedController->_call('isOverwriteDemand', $overwriteDemand));
    }
}
