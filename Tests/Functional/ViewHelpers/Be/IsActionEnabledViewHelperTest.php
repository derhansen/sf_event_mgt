<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Be;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class IsActionEnabledViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function viewHelperReturnsExpectedResultDataProvider(): array
    {
        return [
            'actionNotFoundInSettings' => [
                'unknown',
                [
                    'enabledActions' => [],
                ],
                false,
                false,
            ],
            'actionDisabledInSetting' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 0,
                    ],
                ],
                false,
                false,
            ],
            'actionEnabledInSettingNoAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1,
                    ],
                ],
                false,
                false,
            ],
            'actionEnabledInSettingAccess' => [
                'export',
                [
                    'enabledActions' => [
                        'export' => 1,
                    ],
                ],
                true,
                true,
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsExpectedResultDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedResult(string $action, array $settings, bool $access, bool $expected): void
    {
        $backendUser = $this->getMockBuilder(BackendUserAuthentication::class)->disableOriginalConstructor()->getMock();
        $backendUser->expects(self::any())->method('check')->willReturn($access);
        $GLOBALS['BE_USER'] = $backendUser;

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_BE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:be.isActionEnabled action="export" settings="{settings}" />');
        $context->getVariableProvider()->add('action', $action);
        $context->getVariableProvider()->add('settings', $settings);
        $this->assertEquals($expected, (new TemplateView($context))->render());
    }
}
