<?php
namespace DERHANSEN\SfEventMgt\Tests\Unit\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Service\FluidStandaloneService;
use Nimut\TestingFramework\TestCase\UnitTestCase;
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
        $mockConfigurationManager = $this->getMockBuilder(ConfigurationManager::class)
            ->setMethods(['getConfiguration'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockConfigurationManager->expects($this->once())->method('getConfiguration')->will($this->returnValue([]));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

        $expected = [
            GeneralUtility::getFileAbsFileName('EXT:sf_event_mgt/Resources/Private/Templates/')
        ];

        $this->assertEquals($expected, $this->subject->getTemplateFolders());
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
        $mockConfigurationManager->expects($this->any())->method('getConfiguration')->will($this->returnValue([]));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);

        $mockRequest = $this->getMockBuilder(RenderingContext::class)
            ->setMethods(['setControllerExtensionName', 'setPluginName'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockRequest->expects($this->once())->method('setControllerExtensionName')->with('SfEventMgt');
        $mockRequest->expects($this->once())->method('setPluginName')->with('Pievent');

        $mockEmailView = $this->getMockBuilder(StandaloneView::class)->disableOriginalConstructor()->getMock();
        $mockEmailView->expects($this->any())->method('getRequest')->will($this->returnValue($mockRequest));
        $mockEmailView->expects($this->once())->method('setTemplate')->with('test.html');
        $mockEmailView->expects($this->once())->method('assignMultiple')->with(['key' => 'value']);
        $mockEmailView->expects($this->once())->method('render')->will($this->returnValue('<p>dummy content</p>'));

        $mockObjectManager = $this->getMockBuilder(ObjectManager::class)->disableOriginalConstructor()->getMock();
        $mockObjectManager->expects($this->once())->method('get')->will($this->returnValue($mockEmailView));
        $this->inject($this->subject, 'objectManager', $mockObjectManager);

        $expected = '<p>dummy content</p>';
        $this->assertEquals($expected, $this->subject->renderTemplate('test.html', ['key' => 'value']));
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
        $mockConfigurationManager->expects($this->once())->method('getConfiguration')
            ->will($this->returnValue($settings));
        $this->inject($this->subject, 'configurationManager', $mockConfigurationManager);
        $this->assertSame($expected, $this->subject->getTemplateFolders());
    }
}
