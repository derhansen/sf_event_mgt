<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Registration;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

/**
 * Test case for IsRequiredField viewhelper
 */
class IsRequiredFieldViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];
    private string $templateSource = '<e:registration.isRequiredField settings="{settings}" fieldname="{fieldname}" registrationField="{registrationField}"><f:then>then</f:then><f:else>else</f:else></e:registration.isRequiredField>';

    public static function viewHelperReturnsExpectedResultDataProvider(): array
    {
        $optionalRegistrationField = new Field();
        $optionalRegistrationField->setRequired(false);

        $requiredRegistrationField = new Field();
        $requiredRegistrationField->setRequired(true);

        return [
            'Empty fieldname' => [
                '',
                null,
                [
                    'registration' => [
                        'requiredFields' => 'zip',
                    ],
                ],
                'else'
            ],
            'Fieldname not in settings' => [
                'zip',
                null,
                [
                    'registration' => [
                        'requiredFields' => 'firstname,lastname',
                    ],
                ],
                'else'
            ],
            'Fieldname in settings' => [
                'zip',
                null,
                [
                    'registration' => [
                        'requiredFields' => 'zip,otherfield',
                    ],
                ],
                'then'
            ],
            'Default required field is always required' => [
                'firstname',
                null,
                [
                    'registration' => [
                        'requiredFields' => 'zip,otherfield',
                    ],
                ],
                'then'
            ],
            'No registrationField given' => [
                null,
                null,
                [],
                'else'
            ],
            'Optional registration field' => [
                null,
                $optionalRegistrationField,
                [],
                'else'
            ],
            'Required registration field' => [
                null,
                $requiredRegistrationField,
                [],
                'then'
            ],
        ];
    }

    #[DataProvider('viewHelperReturnsExpectedResultDataProvider')]
    #[Test]
    public function viewHelperRendersReturnsExpectedResult(
        ?string $fieldname,
        ?Field $registrationField,
        array $settings,
        string $expected
    ): void {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource($this->templateSource);
        $context->getVariableProvider()->add('fieldname', $fieldname);
        $context->getVariableProvider()->add('registrationField', $registrationField);
        $context->getVariableProvider()->add('settings', $settings);
        $this->assertEquals($expected, (new TemplateView($context))->render());
    }
}
