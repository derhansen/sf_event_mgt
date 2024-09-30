<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use DERHANSEN\SfEventMgt\Domain\Model\Event;
use DERHANSEN\SfEventMgt\Domain\Repository\EventRepository;
use DERHANSEN\SfEventMgt\Event\EventPidCheckFailedEvent;
use DERHANSEN\SfEventMgt\Utility\PageUtility;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

class EventEvaluationService
{
    public function __construct(
        protected readonly EventRepository $eventRepository,
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly Context $context
    ) {
    }

    /**
     * Evaluates the given event for the detail action
     */
    public function evaluateForDetailAction(RequestInterface $request, array $settings, ?Event $event = null): ?Event
    {
        $event = $this->evaluateSingleEventSetting($settings, $event);
        $event = $this->evaluateIsShortcutSetting($request, $settings, $event);
        $event = $this->evaluateEventPreviewSetting($request, $settings, $event);
        if ($event && is_a($event, Event::class) && ($settings['detail']['checkPidOfEventRecord'] ?? false)) {
            $event = $this->checkPidOfEventRecord($request, $settings, $event);
        }

        return $event;
    }

    /**
     * Evaluates the given event for the ical download action
     */
    public function evaluateForIcalDownloadAction(
        RequestInterface $request,
        array $settings,
        ?Event $event = null
    ): ?Event {
        if ($event && is_a($event, Event::class) && ($settings['detail']['checkPidOfEventRecord'] ?? false)) {
            $event = $this->checkPidOfEventRecord($request, $settings, $event);
        }

        return $event;
    }

    /**
     * Evaluates the given event for the registration action
     */
    public function evaluateForRegistrationAction(
        RequestInterface $request,
        array $settings,
        ?Event $event = null
    ): ?Event {
        $event = $this->evaluateSingleEventSetting($settings, $event);
        if ($event && is_a($event, Event::class) && ($settings['registration']['checkPidOfEventRecord'] ?? false)) {
            $event = $this->checkPidOfEventRecord($request, $settings, $event);
        }

        return $event;
    }

    /**
     * Evaluates the given event for the save registration action
     */
    public function evaluateForSaveRegistrationAction(
        RequestInterface $request,
        array $settings,
        ?Event $event = null
    ): ?Event {
        if ($event && is_a($event, Event::class) && ($settings['registration']['checkPidOfEventRecord'] ?? false)) {
            $event = $this->checkPidOfEventRecord($request, $settings, $event);
        }

        return $event;
    }

    /**
     * If no event is given and the singleEvent setting is set, the configured single event is returned
     */
    private function evaluateSingleEventSetting(array $settings, ?Event $event = null): ?Event
    {
        if ($event === null && (int)($settings['singleEvent'] ?? 0) > 0) {
            $event = $this->eventRepository->findByUid((int)$settings['singleEvent']);
        }

        return $event;
    }

    /**
     * If no event is given and the isShortcut setting is set, the event is displayed using the "Insert Record"
     * content element and should be loaded from contect object data
     */
    private function evaluateIsShortcutSetting(RequestInterface $request, array $settings, ?Event $event): ?Event
    {
        if ($event === null && (bool)($settings['detail']['isShortcut'] ?? false)) {
            $eventRawData = $request->getAttribute('currentContentObject')->data;
            $event = $this->eventRepository->findByUid($eventRawData['uid']);
        }

        return $event;
    }

    /**
     * If no event is given and the the `event_preview` argument is set, the event is displayed for preview
     */
    private function evaluateEventPreviewSetting(RequestInterface $request, array $settings, ?Event $event): ?Event
    {
        if ($event === null && $request->hasArgument('event_preview')) {
            $hasBackendUser = $this->context->getPropertyFromAspect('backend.user', 'isLoggedIn');
            $previewEventId = (int)$request->getArgument('event_preview');
            if ($previewEventId > 0 && $hasBackendUser) {
                if ($settings['previewHiddenRecords'] ?? false) {
                    $event = $this->eventRepository->findByUidIncludeHidden($previewEventId);
                } else {
                    $event = $this->eventRepository->findByUid($previewEventId);
                }
            }
        }

        return $event;
    }

    /**
     * Checks if the event pid could be found in the storagePage settings of the detail plugin and
     * if the pid could not be found it return null instead of the event object.
     */
    private function checkPidOfEventRecord(RequestInterface $request, array $settings, Event $event): ?Event
    {
        $allowedStoragePages = GeneralUtility::intExplode(
            ',',
            PageUtility::extendPidListByChildren(
                $settings['storagePage'] ?? '',
                (int)($settings['recursive'] ?? 0)
            ),
            true
        );
        if (count($allowedStoragePages) > 0 && !in_array($event->getPid(), $allowedStoragePages, true)) {
            $this->eventDispatcher->dispatch(new EventPidCheckFailedEvent($event, $request));
            return null;
        }

        return $event;
    }
}
