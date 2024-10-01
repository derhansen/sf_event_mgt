<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PriceOption extends AbstractEntity
{
    protected string $title = '';
    protected string $description = '';
    protected float $price = 0.0;
    protected ?DateTime $validUntil = null;
    protected ?Event $event = null;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getValidUntil(): ?DateTime
    {
        return $this->validUntil;
    }

    public function setValidUntil(?DateTime $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }

    /**
     * Returns, if the current price option is valid (valid until date not expired)
     */
    public function getIsValid(): bool
    {
        if (!$this->getValidUntil()) {
            return true;
        }

        $compareDate = new DateTime('today midnight');
        return $this->getValidUntil() >= $compareDate;
    }
}
