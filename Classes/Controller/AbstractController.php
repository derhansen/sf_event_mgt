<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\EventDemand;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Controller\Arguments;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\Page\PageInformation;

abstract class AbstractController extends ActionController
{
    protected array $ignoredSettingsForOverwriteDemand = ['storagepage', 'orderfieldallowed', 'ignoreenablefields'];

    /**
     * Public getter for extbase arguments. Can be used by extending extensions in e.g. event listeners to
     * retrieve the current controller arguments.
     */
    public function getControllerArguments(): Arguments
    {
        return $this->arguments;
    }

    /**
     * Public getter to retrieve extension settings. Can be used by extending extensions in e.g. event listeners to
     * retrieve the extension settings.
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Returns an array with variables for the pagination. An array with pagination settings should be passed.
     * Applies default values if settings are not available:
     * - pagination disabled
     * - itemsPerPage = 10
     * - maxNumPages = 10
     */
    protected function getPagination(QueryResultInterface $events, array $settings): array
    {
        $paginationData = [];
        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;
        if (($settings['enablePagination'] ?? false) && (int)$settings['itemsPerPage'] > 0) {
            $paginator = new QueryResultPaginator($events, $currentPage, (int)($settings['itemsPerPage'] ?? 10));
            $pagination = new SlidingWindowPagination($paginator, (int)($settings['maxNumPages'] ?? 10));
            $paginationData = [
                'paginator' => $paginator,
                'pagination' => $pagination,
            ];
        }

        return $paginationData;
    }

    /**
     * Overwrites a given demand object by an propertyName =>  $propertyValue array
     */
    protected function overwriteEventDemandObject(EventDemand $demand, array $overwriteDemand): EventDemand
    {
        foreach ($this->ignoredSettingsForOverwriteDemand as $property) {
            unset($overwriteDemand[$property]);
        }

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $this->ignoredSettingsForOverwriteDemand, true)) {
                continue;
            }
            ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
        }

        return $demand;
    }

    protected function getFrontendPageInformation(): PageInformation
    {
        return $this->request->getAttribute('frontend.page.information');
    }
}
