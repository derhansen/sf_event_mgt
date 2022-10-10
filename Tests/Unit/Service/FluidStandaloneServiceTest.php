<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class FluidStandaloneServiceTest
 */
class FluidStandaloneServiceTest extends UnitTestCase
{
    protected FluidStandaloneService $subject;

    /**
     * Setup
     */
    protected function setUp(): void
    {
        $this->subject = new FluidStandaloneService();
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
    public function getTemplateFoldersReturnsDefaultPathForNoConfiguration()
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

    /**
     * @test
     */
    public function renderTemplateReturnsExpectedResult()
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectConfigurationManager($configurationManager);

        $request = $this->getMockBuilder(Request::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::once())->method('setControllerExtensionName')->willReturn('SfEventMgt');
        $request->expects(self::once())->method('setPluginName')->willReturn('Pievent');

        $standAloneView = $this->getMockBuilder(StandaloneView::class)->disableOriginalConstructor()->getMock();
        $standAloneView->expects(self::any())->method('getRequest')->willReturn($request);
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

    public function templateFoldersDataProvider(): array
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

    /**
     * @test
     * @dataProvider templateFoldersDataProvider
     * @param mixed $settings
     * @param mixed $expected
     */
    public function getTemplateFoldersReturnsExpectedResult($settings, $expected)
    {
        $configurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configurationManager->expects(self::any())->method('getConfiguration')->willReturn($settings);
        $this->subject->injectConfigurationManager($configurationManager);
        self::assertSame($expected, $this->subject->getTemplateFolders());
    }
}
