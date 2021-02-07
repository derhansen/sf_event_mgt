<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use Prophecy\Argument;
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
    /**
     * @var FluidStandaloneService
     */
    protected $subject;

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
        $configurationManager = $this->prophesize(ConfigurationManager::class);
        $configurationManager->getConfiguration(Argument::any(), Argument::any())->willReturn([]);
        $this->subject->injectConfigurationManager($configurationManager->reveal());

        $expected = [
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/')
        ];

        self::assertEquals($expected, $this->subject->getTemplateFolders());
    }

    /**
     * @test
     */
    public function renderTemplateReturnsExpectedResult()
    {
        $configurationManager = $this->prophesize(ConfigurationManager::class);
        $configurationManager->getConfiguration(Argument::any(), Argument::any())->willReturn([]);
        $this->subject->injectConfigurationManager($configurationManager->reveal());

        $request = $this->prophesize(Request::class);
        $request->setControllerExtensionName('SfEventMgt');
        $request->setPluginName('Pievent');

        $view = $this->prophesize(StandaloneView::class);
        $view->getRequest()->willReturn($request);
        $view->setLayoutRootPaths(Argument::any())->shouldBeCalled();
        $view->setPartialRootPaths(Argument::any())->shouldBeCalled();
        $view->setTemplateRootPaths(Argument::any())->shouldBeCalled();
        $view->setTemplate('test.html')->shouldBeCalled();
        $view->setFormat('html')->shouldBeCalled();
        $view->assignMultiple(['key' => 'value'])->shouldbeCalled();
        $view->render()->willReturn('<p>dummy content</p>');
        GeneralUtility::addInstance(StandaloneView::class, $view->reveal());

        $expected = '<p>dummy content</p>';
        self::assertEquals($expected, $this->subject->renderTemplate('test.html', ['key' => 'value']));
    }

    /**
     * @return array
     */
    public function templateFoldersDataProvider()
    {
        return [
            'returnsConfiguredTemplatePaths' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates/',
                            1 => 'fileadmin/user_upload/'
                        ]
                    ]
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/user_upload/')
                ]
            ],
            'ensureSuffixPathIsAdded' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates',
                            1 => 'fileadmin/user_upload'
                        ]
                    ]
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/user_upload/')
                ]
            ],
            'fallbackForOldTemplatePathSetting' => [
                [
                    'view' => [
                        'templateRootPath' => 'EXT:sf_event_mgt/Resources/Private/Templates/'
                    ]
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                ]
            ],
            'ensureArrayKeysAreSorted' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            2 => 'fileadmin/__temp__/',
                            0 => 'EXT:sf_event_mgt/Resources/Private/Templates',
                            1 => 'fileadmin/user_upload/'
                        ]
                    ]
                ],
                [
                    0 => GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    1 => GeneralUtility::getFileAbsFileName('fileadmin/user_upload/'),
                    2 => GeneralUtility::getFileAbsFileName('fileadmin/__temp__/'),
                ]
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
        $configurationManager = $this->prophesize(ConfigurationManager::class);
        $configurationManager->getConfiguration(Argument::any(), Argument::any())->willReturn($settings);
        $this->subject->injectConfigurationManager($configurationManager->reveal());
        self::assertSame($expected, $this->subject->getTemplateFolders());
    }
}
