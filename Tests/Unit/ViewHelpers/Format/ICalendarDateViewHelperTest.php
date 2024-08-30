<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\ViewHelpers;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use DateTime;
use DERHANSEN\SfEventMgt\ViewHelpers\Format\ICalendarDateViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ICalendarDateViewHelperTest extends UnitTestCase
{
    protected bool $resetSingletonInstances = true;

    public static function iCalendarDateDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                '',
            ],
            'dateTimeObject' => [
                new DateTime('@1425234250'),
                '20150301T182410Z',
            ],
        ];
    }

    #[DataProvider('iCalendarDateDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValues(mixed $value, string $expected): void
    {
        $viewHelper = new ICalendarDateViewHelper();
        $viewHelper->setArguments(['date' => $value]);
        $actual = $viewHelper->render();
        self::assertSame($expected, $actual);
    }

    #[Test]
    public function viewHelperRendersChildrenIfNoValueGiven(): void
    {
        $viewHelper = $this->getMockBuilder(ICalendarDateViewHelper::class)
            ->onlyMethods(['renderChildren'])
            ->getMock();
        $viewHelper->expects(self::once())->method('renderChildren')
            ->willReturn(new DateTime('@1425234250'));
        $actual = $viewHelper->render();
        self::assertSame('20150301T182410Z', $actual);
    }
}
