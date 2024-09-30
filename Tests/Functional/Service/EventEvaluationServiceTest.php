<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Tests\Functional\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Service\EventEvaluationService;
use PHPUnit\Framework\Attributes\Test;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\UserAspect;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Note, that all tests actually calls the evaluateForDetailAction() function, which includes all checks that
 * need to be tested
 */
class EventEvaluationServiceTest extends FunctionalTestCase
{
    protected EventEvaluationService $subject;

    protected array $testExtensionsToLoad = ['typo3conf/ext/sf_event_mgt'];

    protected function setUp(): void
    {
        parent::setUp();

        $eventRepository = $this->get(EventRepository::class);
        $eventDispatcher = $this->get(EventDispatcherInterface::class);
        $context = $this->get(Context::class);

        $this->subject = new EventEvaluationService($eventRepository, $eventDispatcher, $context);

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/eventevaluationservice.csv');
    }

    #[Test]
    public function evaluateSingleEventReturnsConfiguredEvent(): void
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        $extbaseRequest = new Request($serverRequest);

        $settings = [
            'singleEvent' => 1,
        ];

        $result = $this->subject->evaluateForDetailAction($extbaseRequest, $settings);
        self::assertNotNull($result);
        self::assertEquals(1, $result->getUid());
    }

    #[Test]
    public function evaluateIsShortcutSettingReturnsConfiguredEvent(): void
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $currentContentObject = $this->createMock(ContentObjectRenderer::class);
        $currentContentObject->data = [
            'uid' => 1.,
        ];

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('currentContentObject', $currentContentObject);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        $extbaseRequest = new Request($serverRequest);

        $settings = [
            'detail' => [
                'isShortcut' => 1,
            ],
        ];

        $result = $this->subject->evaluateForDetailAction($extbaseRequest, $settings);
        self::assertNotNull($result);
        self::assertEquals(1, $result->getUid());
    }

    #[Test]
    public function evaluateEventPreviewSettingReturnsVisibleEventForLoggedInBackendUser(): void
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $currentContentObject = $this->createMock(ContentObjectRenderer::class);
        $currentContentObject->data = [
            'uid' => 1.,
        ];

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setArgument('event_preview', 1);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('currentContentObject', $currentContentObject);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        $extbaseRequest = new Request($serverRequest);

        $settings = [
            'previewHiddenRecords' => 0,
        ];

        $backendUser = new BackendUserAuthentication();
        $backendUser->user['uid'] = 1;
        $this->get(Context::class)->setAspect('backend.user', new UserAspect($backendUser));

        $result = $this->subject->evaluateForDetailAction($extbaseRequest, $settings);
        self::assertNotNull($result);
        self::assertEquals(1, $result->getUid());
    }

    #[Test]
    public function evaluateEventPreviewSettingReturnsNoEventForNoBackendUser(): void
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $currentContentObject = $this->createMock(ContentObjectRenderer::class);
        $currentContentObject->data = [
            'uid' => 1,
        ];

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setArgument('event_preview', 1);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('currentContentObject', $currentContentObject);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        $extbaseRequest = new Request($serverRequest);

        $settings = [
            'previewHiddenRecords' => 0,
        ];

        $result = $this->subject->evaluateForDetailAction($extbaseRequest, $settings);
        self::assertNull($result);
    }

    #[Test]
    public function evaluateEventPreviewSettingReturnsHiddenEventForLoggedInBackendUserAndPreviewHiddenRecords(): void
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray([]);

        $currentContentObject = $this->createMock(ContentObjectRenderer::class);
        $currentContentObject->data = [
            'uid' => 3,
        ];

        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $extbaseRequestParameters->setArgument('event_preview', 3);
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('frontend.typoscript', $frontendTypoScript)
            ->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE)
            ->withAttribute('currentContentObject', $currentContentObject);
        $this->get(ConfigurationManagerInterface::class)->setRequest($serverRequest);

        $extbaseRequest = new Request($serverRequest);

        $settings = [
            'previewHiddenRecords' => 1,
        ];

        $backendUser = new BackendUserAuthentication();
        $backendUser->user['uid'] = 1;
        $this->get(Context::class)->setAspect('backend.user', new UserAspect($backendUser));

        $result = $this->subject->evaluateForDetailAction($extbaseRequest, $settings);
        self::assertNotNull($result);
        self::assertEquals(3, $result->getUid());
    }

    #[Test]
    public function checkPidOfEventRecordReturnsNullIfNoEventGiven(): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = new Request($serverRequest);

        self::assertNull($this->subject->evaluateForDetailAction($extbaseRequest, []));
    }

    #[Test]
    public function checkPidOfEventRecordReturnsGivenEventIfCheckPidOfEventRecordDisabled(): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = new Request($serverRequest);

        $event = new Event();
        $event->setTitle('Test event');

        self::assertEquals($event, $this->subject->evaluateForDetailAction($extbaseRequest, [], $event));
    }

    #[Test]
    public function checkPidOfEventRecordReturnsGivenEventIfCheckPidOfEventRecordEnabledNoStoragePage(): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = new Request($serverRequest);

        $event = new Event();
        $event->setTitle('Test event');

        $settings = [
            'detail' => [
                'checkPidOfEventRecord' => true,
            ],
        ];

        self::assertEquals($event, $this->subject->evaluateForDetailAction($extbaseRequest, $settings, $event));
    }

    #[Test]
    public function checkPidOfEventRecordReturnsNullIfCheckPidOfEventRecordEnabledAndNotInStoragePage(): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = new Request($serverRequest);

        $event = new Event();
        $event->setTitle('Test event');

        $settings = [
            'detail' => [
                'checkPidOfEventRecord' => true,
            ],
            'storagePage' => '1',
        ];

        $this->assertnull($this->subject->evaluateForDetailAction($extbaseRequest, $settings, $event));
    }

    #[Test]
    public function checkPidOfEventRecordReturnsEventIfCheckPidOfEventRecordEnabledAndInStoragePage(): void
    {
        $extbaseRequestParameters = new ExtbaseRequestParameters();
        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('extbase', $extbaseRequestParameters)
            ->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
        $extbaseRequest = new Request($serverRequest);

        $event = new Event();
        $event->setPid(1);
        $event->setTitle('Test event');

        $settings = [
            'detail' => [
                'checkPidOfEventRecord' => true,
            ],
            'storagePage' => '1',
        ];

        self::assertEquals($event, $this->subject->evaluateForDetailAction($extbaseRequest, $settings, $event));
    }
}
