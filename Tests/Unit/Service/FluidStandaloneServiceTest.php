<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class FluidStandaloneServiceTest
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->setMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockConfigurationManager->expects(self::once())->method('getConfiguration')->willReturn([]);
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

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
        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->setMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockConfigurationManager->expects(self::any())->method('getConfiguration')->willReturn([]);
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

        $mockRequest = $this->getMockBuilder(RenderingContext::class)
            ->setMethods(['setControllerExtensionName', 'setPluginName'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects(self::once())->method('setControllerExtensionName')->with('SfEventMgt');
        $mockRequest->expects(self::once())->method('setPluginName')->with('Pievent');

        $mockEmailView = $this->getMockBuilder(StandaloneView::class)->disableOriginalConstructor()->getMock();
        $mockEmailView->expects(self::any())->method('getRequest')->willReturn($mockRequest);
        $mockEmailView->expects(self::once())->method('setTemplate')->with('test.html');
        $mockEmailView->expects(self::once())->method('assignMultiple')->with(['key' => 'value']);
        $mockEmailView->expects(self::once())->method('render')->willReturn('<p>dummy content</p>');

        $mockObjectManager = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $mockObjectManager->expects(self::once())->method('get')->willReturn($mockEmailView);
        $this->inject($this->subject, 'objectManager', $mockObjectManager);

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
        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->setMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockConfigurationManager->expects(self::once())->method('getConfiguration')
            ->willReturn($settings);
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);
        self::assertSame($expected, $this->subject->getTemplateFolders());
    }
}
