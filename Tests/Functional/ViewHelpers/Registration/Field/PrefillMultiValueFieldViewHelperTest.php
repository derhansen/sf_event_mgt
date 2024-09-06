<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class PrefillMultiValueFieldViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function viewHelperReturnsExpectedResultIfNoOriginalRequestDataProvider(): array
    {
        return [
            'Default value selected' => [
                'Default',
                'Default',
                true,
            ],
            'Default value not selected' => [
                'Default',
                'Foo',
                false,
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsExpectedResultIfNoOriginalRequestDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedResultIfNoOriginalRequest(
        string $defaultValue,
        string $currentValue,
        bool $expected
    ): void {
        $field = new Field();
        $field->setDefaultValue($defaultValue);

        $frontendUser = new FrontendUserAuthentication();
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillMultiValueField registrationField="{field}" currentValue="{value}" />');
        $context->getVariableProvider()->add('field', $field);
        $context->getVariableProvider()->add('value', $currentValue);
        self::assertEquals($expected, (new TemplateView($context))->render());
    }

    public static function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider(): array
    {
        return [
            'submitted value is field value for string' => [
                1,
                1,
                'option1',
                'option1',
                true,
            ],
            'submitted value is field value for array' => [
                1,
                1,
                'option1',
                ['option1', 'option2'],
                true,
            ],
            'submitted value is not field value for array' => [
                1,
                1,
                'option3',
                ['option1', 'option2'],
                false,
            ],
            'submitted registration field uid is not registration field uid' => [
                1,
                2,
                'option1',
                ['option1', 'option2'],
                false,
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist(
        int $submittedRegistrationFieldUid,
        int $registrationFieldUid,
        string $currentValue,
        mixed $fieldValue,
        bool $expected
    ): void {
        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => [
                        $submittedRegistrationFieldUid => $fieldValue,
                    ],
                ],
            ],
        ];

        $field = $this->getMockBuilder(Field::class)->getMock();
        $field->expects(self::any())->method('getUid')->willReturn($registrationFieldUid);

        $originalExtbaseRequestParameters = new ExtbaseRequestParameters();
        $originalExtbaseRequestParameters->setPluginName('Pievent');
        $originalExtbaseRequestParameters->setControllerExtensionName('SfEventMgt');

        $originalServerRequest = new ServerRequest();
        $originalServerRequest = $originalServerRequest->withAttribute('extbase', $originalExtbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $originalExtbaseRequest = (new Request($originalServerRequest));

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pievent');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $extbaseRequestParameters->setOriginalRequest($originalExtbaseRequest);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillMultiValueField registrationField="{field}" currentValue="{value}" />');
        $context->getVariableProvider()->add('field', $field);
        $context->getVariableProvider()->add('value', $currentValue);
        self::assertEquals($expected, (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsFalseIfOriginalRequestHasNoRegistrationFieldValues(): void
    {
        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => [],
                ],
            ],
        ];

        $field = $this->getMockBuilder(Field::class)->getMock();
        $field->expects(self::any())->method('getUid')->willReturn(1);

        $originalExtbaseRequestParameters = new ExtbaseRequestParameters();
        $originalExtbaseRequestParameters->setPluginName('Pievent');
        $originalExtbaseRequestParameters->setControllerExtensionName('SfEventMgt');

        $originalServerRequest = new ServerRequest();
        $originalServerRequest = $originalServerRequest->withAttribute('extbase', $originalExtbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $originalExtbaseRequest = (new Request($originalServerRequest));

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pievent');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $extbaseRequestParameters->setOriginalRequest($originalExtbaseRequest);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillMultiValueField registrationField="{field}" currentValue="{value}" />');
        $context->getVariableProvider()->add('field', $field);
        $context->getVariableProvider()->add('value', 'foo');
        self::assertFalse((new TemplateView($context))->render());
    }
}
