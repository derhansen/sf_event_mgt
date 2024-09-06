<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Validation;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class ErrorClassViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function fieldnameDataProvider(): array
    {
        return [
            'No fieldname' => [
                '',
                '',
                'error-class',
                '',
                '',
            ],
            'No error for fieldname' => [
                'firstname',
                '',
                'error-class',
                'registration.lastname',
                'Error message for registration.lastname',
            ],
            'Error for fieldname with default class name' => [
                'firstname',
                'error-class',
                'error-class',
                'registration.firstname',
                'Error message for registration.firstname',
            ],
            'Error for fieldname with custom class name' => [
                'firstname',
                'custom-class',
                'custom-class',
                'registration.firstname',
                'Error message for registration.firstname',
            ],
        ];
    }

    #[DataProvider('fieldnameDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedStringForFieldname(
        string $fieldname,
        string $expected,
        string $errorClass,
        string $errorForProperty,
        string $errorMessageForProperty
    ): void {
        $result = new Result();
        if ($errorForProperty) {
            $result->forProperty($errorForProperty)->addError(new Error($errorMessageForProperty, 1234567890));
        }

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setOriginalRequestMappingResults($result);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:validation.errorClass fieldname="{fieldname}" class="{class}" />');
        $context->getVariableProvider()->add('fieldname', $fieldname);
        $context->getVariableProvider()->add('class', $errorClass);
        $result = (new TemplateView($context))->render();

        self::assertEquals($expected, $result);
    }

    public static function registrationFieldDataProvider(): array
    {
        $field = new Field();
        $field->_setProperty('uid', 2);

        return [
            'No registration field' => [
                null,
                '',
                'error-class',
                '',
                '',
            ],
            'No error for registration field' => [
                $field,
                '',
                'error-class',
                'registration.fields.1',
                'Error message for registration.fields.1',
            ],
            'Error for fieldname with default class name' => [
                $field,
                'error-class',
                'error-class',
                'registration.fields.2',
                'Error message for registration.fields.1',
            ],
            'Error for fieldname with custom class name' => [
                $field,
                'custom-class',
                'custom-class',
                'registration.fields.2',
                'Error message for registration.fields.1',
            ],
        ];
    }

    #[DataProvider('registrationFieldDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedStringForRegistrationField(
        ?Field $registrationField,
        string $expected,
        string $errorClass,
        string $errorForProperty,
        string $errorMessageForProperty
    ): void {
        $result = new Result();
        if ($errorForProperty) {
            $result->forProperty($errorForProperty)->addError(new Error($errorMessageForProperty, 1234567890));
        }

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setOriginalRequestMappingResults($result);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:validation.errorClass registrationField="{registrationField}" class="{class}" />');
        $context->getVariableProvider()->add('registrationField', $registrationField);
        $context->getVariableProvider()->add('class', $errorClass);
        $result = (new TemplateView($context))->render();

        self::assertEquals($expected, $result);
    }
}
