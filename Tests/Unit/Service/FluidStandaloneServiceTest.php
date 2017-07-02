<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use \DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use TYPO3\CMS\Core\Configuration\ConfigurationManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    protected $subject = null;


    /**
     * Setup
     *
     * @return void
     */
    protected function setUp()
    {
        $this->subject = new FluidStandaloneService();
    }

    /**
     * Teardown
     *
     * @return void
     */
    protected function tearDown()
    {
        unset($this->subject);
    }

    /**
     * @test
     */
    public function getTemplateFoldersReturnsDefaultPathForNoConfiguration()
    {
        $mockConfigurationManager = $this->getMock(ConfigurationManager::class, ['getConfiguration'], [], '', false);
        $mockConfigurationManager->expects($this->once())->method('getConfiguration')->will($this->returnValue([]));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

        $expected = [
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/')
        ];

        $this->assertEquals($expected, $this->subject->getTemplateFolders());
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
                            'EXT:sf_event_mgt/Resources/Private/Templates/',
                            'fileadmin/templates/events/Templates/'
                        ]
                    ]
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/templates/events/Templates/')
                ]
            ],
            'ensureSuffixPathIsAdded' => [
                [
                    'view' => [
                        'templateRootPaths' => [
                            'EXT:sf_event_mgt/Resources/Private/Templates',
                            'fileadmin/templates/events/Templates'
                        ]
                    ]
                ],
                [
                    GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/'),
                    GeneralUtility::getFileAbsFileName('fileadmin/templates/events/Templates/')
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
            ]
        ];
    }

    /**
     * @test
     * @dataProvider templateFoldersDataProvider
     */
    public function getTemplateFoldersReturnsExpectedResult($settings, $expected)
    {
        $mockConfigurationManager = $this->getMock(ConfigurationManager::class, ['getConfiguration'], [], '', false);
        $mockConfigurationManager->expects($this->once())->method('getConfiguration')
            ->will($this->returnValue($settings));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);
        $this->assertSame($expected, $this->subject->getTemplateFolders());
    }

    /**
     * @test
     */
    public function getTemplatePathReturnsLastItemOfPossibleTemplatePaths()
    {
        $mockFluidStandaloneService = $this->getMock(FluidStandaloneService::class, ['getTemplatePaths'], [],
            '', false);
        $mockFluidStandaloneService->expects($this->once())->method('getTemplatePaths')
            ->will($this->returnValue([
                GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/test.html'),
                GeneralUtility::getFileAbsFileName('fileadmin/templates/events/Templates/test.html')
            ]));

        $expected = GeneralUtility::getFileAbsFileName('fileadmin/templates/events/Templates/') . 'test.html';
        $this->assertEquals($expected, $mockFluidStandaloneService->getTemplatePath('test.html'));
    }

    /**
     * @test
     */
    public function renderTemplateReturnsExpectedResult()
    {
        $mockConfigurationManager = $this->getMock(ConfigurationManager::class, ['getConfiguration'], [], '', false);
        $mockConfigurationManager->expects($this->any())->method('getConfiguration')->will($this->returnValue([]));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

        $mockRequest = $this->getMock(RenderingContext::class, ['setControllerExtensionName', 'setPluginName'], [],
            '', false);
        $mockRequest->expects($this->once())->method('setControllerExtensionName')->with('SfEventMgt');
        $mockRequest->expects($this->once())->method('setPluginName')->with('Pievent');

        $mockEmailView = $this->getMock(StandaloneView::class, [], [], '', false);
        $mockEmailView->expects($this->any())->method('getRequest')->will($this->returnValue($mockRequest));
        $mockEmailView->expects($this->once())->method('setTemplatePathAndFilename')->with('test.html');
        $mockEmailView->expects($this->once())->method('assignMultiple')->with(['key' => 'value']);
        $mockEmailView->expects($this->once())->method('render')->will($this->returnValue('<p>dummy content</p>'));

        $mockObjectManager = $this->getMock(ObjectManager::class, [], [], '', false);
        $mockObjectManager->expects($this->once())->method('get')->will($this->returnValue($mockEmailView));
        $this->inject($this->subject, 'objectManager', $mockObjectManager);

        $expected = '<p>dummy content</p>';
        $this->assertEquals($expected, $this->subject->renderTemplate('test.html', ['key' => 'value']));
    }
}
