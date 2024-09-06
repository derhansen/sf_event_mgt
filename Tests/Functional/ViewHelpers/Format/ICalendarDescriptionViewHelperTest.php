<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\ViewHelpers\Format;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3Fluid\Fluid\View\TemplateView;

class ICalendarDescriptionViewHelperTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    public static function iCalendarDescriptionDataProvider(): array
    {
        return [
            'emptyValue' => [
                '',
                12,
                '',
            ],
            'shortDescriptionLess75Chars' => [
                'This is just a short text with less than 75 chars',
                12,
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtml' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars',
                12,
                'This is just a short text with less than 75 chars',
            ],
            'shortDescriptionLess75CharsWithHtmlAndLineBreak' => [
                'This is just a short text <b>with</b> less&nbsp;than 75 chars' . chr(13) . ' and some more text',
                12,
                'This is just a short text with less than 75 chars\n\n and some ' . chr(10) . ' more text',
            ],
            'longDescriptionWithoutLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                12,
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, s' . chr(10) . ' ed diam',
            ],
            'longDescriptionWithLineBreaks' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam ' . chr(13) . 'nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                12,
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed di' . chr(10) . ' am \n\nnonumy eirmod tempor invidunt ut labore et dolore magna aliquyam era' . chr(10) . ' t, sed diam',
            ],
            'longDescriptionWithDifferentSubstractCharsOption' => [
                'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam',
                48,
                'Lorem ipsum dolor sit amet,' . chr(10) . '  consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut lab' . chr(10) . ' ore et dolore magna aliquyam erat, sed diam',
            ],
        ];
    }

    #[DataProvider('iCalendarDescriptionDataProvider')]
    #[Test]
    public function viewHelperReturnsExpectedValues(string $value, int $substractChars, string $expected): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = (new Request($serverRequest));
        $context = $this->get(RenderingContextFactory::class)->create([], $extbaseRequest);
        $context->getViewHelperResolver()->addNamespace('e', 'DERHANSEN\\SfEventMgt\\ViewHelpers');
        $context->getTemplatePaths()->setTemplateSource('<e:format.ICalendarDescription description="{description}" substractChars="{substractChars}" />');
        $context->getVariableProvider()->add('description', $value);
        $context->getVariableProvider()->add('substractChars', $substractChars);
        self::assertEquals($expected, (new TemplateView($context))->render());
    }
}
