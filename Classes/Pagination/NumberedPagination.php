<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Pagination;

use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;

final class NumberedPagination implements PaginationInterface
{
    protected PaginatorInterface $paginator;

    protected int $maximumNumberOfLinks = 10;
    protected int $displayRangeStart = 0;
    protected int $displayRangeEnd = 0;
    protected bool $hasLessPages = false;
    protected bool $hasMorePages = false;

    public function __construct(PaginatorInterface $paginator, int $maximumNumberOfLinks = 0)
    {
        $this->paginator = $paginator;
        if ($maximumNumberOfLinks > 0) {
            $this->maximumNumberOfLinks = $maximumNumberOfLinks;
        }
        $this->calculateDisplayRange();
    }

    public function getPreviousPageNumber(): ?int
    {
        $previousPage = $this->paginator->getCurrentPageNumber() - 1;

        if ($previousPage > $this->paginator->getNumberOfPages()) {
            return null;
        }

        return $previousPage >= $this->getFirstPageNumber()
            ? $previousPage
            : null;
    }

    public function getNextPageNumber(): ?int
    {
        $nextPage = $this->paginator->getCurrentPageNumber() + 1;

        return $nextPage <= $this->paginator->getNumberOfPages()
            ? $nextPage
            : null;
    }

    public function getFirstPageNumber(): int
    {
        return 1;
    }

    public function getLastPageNumber(): int
    {
        return $this->paginator->getNumberOfPages();
    }

    public function getStartRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfFirstPaginatedItem() + 1;
    }

    public function getEndRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfLastPaginatedItem() + 1;
    }

    /**
     * @return int[]
     */
    public function getAllPageNumbers(): array
    {
        return range($this->displayRangeStart, $this->displayRangeEnd);
    }

    public function getHasLessPages(): bool
    {
        return $this->hasLessPages;
    }

    public function getHasMorePages(): bool
    {
        return $this->hasMorePages;
    }

    public function getMaximumNumberOfLinks(): int
    {
        return $this->maximumNumberOfLinks;
    }

    public function getDisplayRangeStart(): int
    {
        return $this->displayRangeStart;
    }

    public function getDisplayRangeEnd(): int
    {
        return $this->displayRangeEnd;
    }

    protected function calculateDisplayRange(): void
    {
        $numberOfPages = $this->paginator->getNumberOfPages();
        $currentPage = $this->paginator->getCurrentPageNumber();

        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        if ($maximumNumberOfLinks > $numberOfPages) {
            $maximumNumberOfLinks = $numberOfPages;
        }
        $delta = floor($maximumNumberOfLinks / 2);
        $this->displayRangeStart = (int)($currentPage - $delta);
        $this->displayRangeEnd = (int)($currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0));
        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }
        if ($this->displayRangeEnd > $numberOfPages) {
            $this->displayRangeStart -= $this->displayRangeEnd - $numberOfPages;
        }
        $this->displayRangeStart = (int)max($this->displayRangeStart, 1);
        $this->displayRangeEnd = (int)min($this->displayRangeEnd, $numberOfPages);
        $this->hasLessPages = $this->displayRangeStart > 2;
        $this->hasMorePages = $this->displayRangeEnd + 1 < $this->paginator->getNumberOfPages();
    }

    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }
}
