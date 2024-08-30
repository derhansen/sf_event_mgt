<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class FluidStandaloneServiceTest extends UnitTestCase
{
    protected FluidStandaloneService $subject;

    protected function setUp(): void
    {
        $this->subject = new FluidStandaloneService();
    }

    protected function tearDown(): void
    {
        unset($this->subject);
    }

    #[Test]
    public function getTemplateFoldersReturnsDefaultPathForNoConfiguration(): void
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectConfigurationManager($configurationManager);

        $expected = [
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
        ];

        self::assertEquals($expected, $this->subject->getTemplateFolders());
    }

    #[Test]
    public function renderTemplateReturnsExpectedResult(): void
    {
        $serverRequest = new ServerRequest();
        $GLOBALS['TYPO3_REQUEST'] = $serverRequest;

        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectConfigurationManager($configurationManager);

        $extbaseRequestParams = new ExtbaseRequestParameters();
        $extbaseRequestParams->setPluginName('Pieventregistration');
        $extbaseRequestParams->setControllerExtensionName('SfEventMgt');

        $extbaseRequest = GeneralUtility::makeInstance(Request::class, $serverRequest->withAttribute('extbase', $extbaseRequestParams));

        $standAloneView = $this->createMock(StandaloneView::class);
        $standAloneView->expects(self::once())->method('setRequest')->with($extbaseRequest);
        $standAloneView->expects(self::once())->method('setLayoutRootPaths');
        $standAloneView->expects(self::once())->method('setPartialRootPaths');
        $standAloneView->expects(self::once())->method('setTemplateRootPaths');
        $standAloneView->expects(self::once())->method('setTemplate')->with('test.html');
        $standAloneView->expects(self::once())->method('setFormat')->with('html');
        $standAloneView->expects(self::once())->method('assignMultiple')->with(['key' => 'value']);
        $standAloneView->expects(self::once())->method('render')->willReturn('<p>dummy content</p>');
        GeneralUtility::addInstance(StandaloneView::class, $standAloneView);

        $expected = '<p>dummy content</p>';
        self::assertEquals($expected, $this->subject->renderTemplate('test.html', ['key' => 'value']));
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
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/user_upload/'),
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
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/user_upload/'),
                ],
            ],
            'fallbackForOldTemplatePathSetting' => [
                [
                    'view' => [
                        'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/',
                    ],
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
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
                    0 => GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    1 => GeneralUtility::getFileAbsFileName('fileadmin/user_upload/'),
                    2 => GeneralUtility::getFileAbsFileName('fileadmin/__temp__/'),
                ],
            ],
        ];
    }

    #[DataProvider('templateFoldersDataProvider')]
    #[Test]
    public function getTemplateFoldersReturnsExpectedResult(array $settings, array $expected): void
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManager->expects(self::any())->method('getConfiguration')->willReturn($settings);
        $this->subject->injectConfigurationManager($configurationManager);
        self::assertSame($expected, $this->subject->getTemplateFolders());
    }
}
