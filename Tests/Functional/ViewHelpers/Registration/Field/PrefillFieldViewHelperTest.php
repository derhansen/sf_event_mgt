<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Registration\Field;

use DateTime;
use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field\PrefillFieldViewHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class PrefillFieldViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    #[Test]
    public function viewHelperReturnsFieldDefaultValue(): void
    {
        $field = new Field();
        $field->setDefaultValue('Default');

        $frontendUser = new FrontendUserAuthentication();
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillField registrationField="{field}" />');
        $context->getVariableProvider()->add('field', $field);
        $this->assertEquals('Default', (new TemplateView($context))->render());
    }

    #[Test]
    public function viewHelperReturnsFieldFeUserValue(): void
    {
        $field = new Field();
        $field->setFeuserValue('first_name');

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillField registrationField="{field}" />');
        $context->getVariableProvider()->add('field', $field);
        $this->assertEquals('John', (new TemplateView($context))->render());
    }

    public static function viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider(): array
    {
        return [
            'submitted value returned' => [
                1,
                [
                    '1' => 'Submitted value',
                ],
                'Submitted value',
            ],
            'empty value returned if not found' => [
                2,
                [
                    '1' => 'Submitted value',
                ],
                '',
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsSubmittedValueIfOriginalRequestExistDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValueIfOriginalRequestExist(
        int $fieldUid,
        array $fieldValues,
        string $expected
    ): void {
        $submittedData = [
            'tx_sfeventmgt_pievent' => [
                'registration' => [
                    'fields' => $fieldValues,
                ],
            ],
        ];

        $frontendUser = new FrontendUserAuthentication();
        $frontendUser->user = [
            'first_name' => 'John',
        ];

        $field = $this->createMock(Field::class);
        $field->expects(self::any())->method('getUid')->willReturn($fieldUid);

        $originalExtbaseRequestParameters = new ExtbaseRequestParameters();
        $originalExtbaseRequestParameters->setPluginName('Pievent');
        $originalExtbaseRequestParameters->setControllerExtensionName('SfEventMgt');

        $originalServerRequest = new ServerRequest();
        $originalServerRequest = $originalServerRequest->withAttribute('extbase', $originalExtbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $originalExtbaseRequest = (new Request($originalServerRequest));

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setPluginName('Pievent');
        $extbaseRequestParameters->setControllerExtensionName('SfEventMgt');
        $extbaseRequestParameters->setOriginalRequest($originalExtbaseRequest);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withParsedBody($submittedData)
            ->withAttribute('frontend.user', $frontendUser)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:registration.field.prefillField registrationField="{field}" />');
        $context->getVariableProvider()->add('field', $field);
        $this->assertEquals($expected, (new TemplateView($context))->render());
    }
}
