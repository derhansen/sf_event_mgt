<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

use DateTime;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SearchDemand
{
    protected string $search = '';
    protected string $fields = '';
    protected ?DateTime $startDate = null;
    protected ?DateTime $endDate = null;

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search): void
    {
        $this->search = $search;
    }

    public function getFields(): string
    {
        return $this->fields;
    }

    public function setFields(string $fields): void
    {
        $this->fields = $fields;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * Returns if the demand object has at least one search property set
     */
    public function getHasQuery(): bool
    {
        return $this->search !== '' || $this->startDate !== null || $this->endDate !== null;
    }

    public function toArray(): array
    {
        return [
            'search' => $this->search,
            'fields' => $this->fields,
            'startDate' => $this->startDate ? $this->startDate->format(DateTime::RFC3339) : null,
            'endDate' => $this->endDate ? $this->endDate->format(DateTime::RFC3339) : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        $demand = GeneralUtility::makeInstance(SearchDemand::class);
        $demand->setSearch($data['search'] ?? '');
        $demand->setFields($data['fields'] ?? '');
        if (isset($data['startDate'])) {
            $startDate = DateTime::createFromFormat(DateTime::RFC3339, (string)$data['startDate']);
            $startDate = $startDate !== false ? $startDate : null;
            $demand->setStartDate($startDate);
        }
        if (isset($data['endDate'])) {
            $endDate = DateTime::createFromFormat(DateTime::RFC3339, (string)$data['endDate']);
            $endDate = $endDate !== false ? $endDate : null;
            $demand->setEndDate($endDate);
        }

        return $demand;
    }
}
