<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use DERHANSEN\SfEventMgt\Service\FluidRenderingService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\ViewHelpers\ConstantViewHelper;

class FluidRenderingServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    #[Test]
    public function parseStringFluidReturnsExpectedResultForSimpleVariable(): void
    {
        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $expected = 'This is a subject line with a variable';
        $fluidString = 'This is a subject line with a {variable}';

        self::assertEquals(
            $expected,
            $subject->parseString($this->getExtbaseRequest(), $fluidString, ['variable' => 'variable'])
        );
    }

    #[Test]
    public function parseStringFluidReturnsExpectedResultForExtbaseDomainObjectVariable(): void
    {
        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $expected = 'Hello Torben Hansen';
        $fluidString = 'Hello {registration.firstname} {registration.lastname}';

        $registration = new Registration();
        $registration->setFirstname('Torben');
        $registration->setLastname('Hansen');
        $registration->setEmail('torben@derhansen.com');

        self::assertEquals(
            $expected,
            $subject->parseString($this->getExtbaseRequest(), $fluidString, ['registration' => $registration])
        );
    }

    #[Test]
    public function parseStringExecutesAllowedViewHelper(): void
    {
        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $fluidString = 'Date: {date -> f:format.date(format: \'d.m.Y\')}';

        $result = $subject->parseString(
            $this->getExtbaseRequest(),
            $fluidString,
            ['date' => new \DateTime('2026-07-14')]
        );

        self::assertStringContainsString('Date: 14.07.2026', $result);
        self::assertStringNotContainsString('not allowed', $result);
    }

    #[Test]
    public function parseStringBlocksDisallowedConstantViewHelper(): void
    {
        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $fluidString = 'Value: {f:constant(name: \'PHP_INT_MAX\')}';

        $result = $subject->parseString($this->getExtbaseRequest(), $fluidString);

        self::assertStringNotContainsString((string)PHP_INT_MAX, $result);
        self::assertStringNotContainsString('f:constant', $result);
    }

    #[Test]
    public function parseStringBlocksDisallowedCObjectViewHelper(): void
    {
        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $fluidString = '{f:cObject(typoscriptObjectPath: \'lib.test\')}';

        $result = $subject->parseString($this->getExtbaseRequest(), $fluidString);
        self::assertStringNotContainsString('f:cObject', $result);
    }

    #[Test]
    public function parseStringExecutesAdditionallyAllowedViewHelper(): void
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['parseStringFluid']['additionalAllowedViewHelpers'] = [
            ConstantViewHelper::class,
        ];

        $configurationManager = $this->get(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $subject = new FluidRenderingService($configurationManager, $viewFactory);

        $fluidString = 'Value: {f:constant(name: \'PHP_INT_MAX\')}';

        $result = $subject->parseString($this->getExtbaseRequest(), $fluidString);

        self::assertStringContainsString('Value: ' . PHP_INT_MAX, $result);
        self::assertStringNotContainsString('f:constant', $result);
    }

    public static function templateFoldersDataProvider(): array
    {
        return [
            'returnsConfiguredTemplatePaths' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates/',
                            1 => 'fileadmin/user_upload/',
                        ],
                    ],
                ],
                [
                    'EXT:sf_event_mgt/Resources/Private/Templates/',
                    'fileadmin/user_upload/',
                ],
            ],
            'ensureSuffixPathIsAdded' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates',
                            1 => 'fileadmin/user_upload',
                        ],
                    ],
                ],
                [
                    'EXT:sf_event_mgt/Resources/Private/Templates/',
                    'fileadmin/user_upload/',
                ],
            ],
            'fallbackForOldTemplatePathSetting' => [
                [
                    'view' => [
                        'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
                    ],
                ],
                [
                    'EXT:sf_event_mgt/Resources/Private/Templates/',
                ],
            ],
            'ensureArrayKeysAreSorted' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            2 => 'fileadmin/__temp__/',
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates',
                            1 => 'fileadmin/user_upload/',
                        ],
                    ],
                ],
                [
                    0 => 'EXT:sf_event_mgt/Resources/Private/Templates/',
                    1 => 'fileadmin/user_upload/',
                    2 => 'fileadmin/__temp__/',
                ],
            ],
        ];
    }

    #[DataProvider('templateFoldersDataProvider')]
    #[Test]
    public function getTemplateFoldersReturnsExpectedResult(array $settings, array $expected): void
    {
        $configurationManager = $this->createMock(ConfigurationManager::class);
        $configurationManager->method('getConfiguration')->willReturn($settings);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $mock = $this->getAccessibleMock(FluidRenderingService::class, null, [$configurationManager, $viewFactory]);
        $result = $mock->_call('getTemplateFolders');

        $expected = array_map(static fn($item) => GeneralUtility::getFileAbsFileName($item), $expected);
        self::assertSame($expected, $result);
    }

    #[Test]
    public function getTemplateFoldersReturnsDefaultPathForNoConfiguration(): void
    {
        $configurationManager = $this->createMock(ConfigurationManager::class);
        $viewFactory = $this->get(ViewFactoryInterface::class);
        $mock = $this->getAccessibleMock(FluidRenderingService::class, null, [$configurationManager, $viewFactory]);
        $result = $mock->_call('getTemplateFolders');

        $expected = [GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/')];
        self::assertSame($expected, $result);
    }

    protected function getExtbaseRequest(): RequestInterface
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        return new Request($serverRequest);
    }
}
