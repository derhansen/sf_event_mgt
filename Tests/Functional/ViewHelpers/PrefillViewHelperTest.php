<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class PrefillViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    #[Test]
    public function viewHelperReturnsEmptyStringIfFrontendUserNotAvailable(): void
    {
        $frontendUser = new FrontendUserAuthentication();
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        self::assertEquals('', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsCurrentFieldValueIfValueInParsedBodyAvailable(): void
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        self::assertEquals('Torben', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfPrefillSettingsEmpty(): void
    {
        $submittedData = [];
        $settings = [];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        $context->getVariableProvider()->add('settings', $settings);
        self::assertEquals('', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInPrefillSettings(): void
    {
        $submittedData = [];
        $settings = [
            'registration' => [
                'prefillFields' => [
                    'lastname' => 'last_name',
                ],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        $context->getVariableProvider()->add('settings', $settings);
        self::assertEquals('', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsEmptyStringIfFieldNotFoundInFeUser(): void
    {
        $submittedData = [];
        $settings = [
            'registration' => [
                'prefillFields' => [
                    'non_existing' => 'non_existing_field',
                ],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="non_existing" prefillSettings="{settings.registration.prefillFields}" />');
        $context->getVariableProvider()->add('settings', $settings);
        self::assertEquals('', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsFieldvalueIfFound(): void
    {
        $submittedData = [];
        $settings = [
            'registration' => [
                'prefillFields' => [
                    'firstname' => 'first_name',
                ],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        $context->getVariableProvider()->add('settings', $settings);
        self::assertEquals('John', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsSubmittedValueIfValidationError(): void
    {
        $submittedData = [
            'tx_sfeventmgt_pieventregistration' => [
                'registration' => ['firstname' => 'Torben'],
            ],
        ];

        $settings = [
            'registration' => [
                'prefillFields' => [
                    'firstname' => 'first_name',
                ],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pieventregistration');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:prefill fieldname="firstname" prefillSettings="{settings.registration.prefillFields}" />');
        $context->getVariableProvider()->add('settings', $settings);
        self::assertEquals('Torben', (new TemplateView($context))->render());
    }
}
