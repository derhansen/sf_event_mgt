<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Price option
 */
class PriceOption extends AbstractEntity
{
    /**
     * Price
     *
     * @var float
     */
    protected $price = 0.0;

    /**
     * Valid until
     *
     * @var DateTime
     */
    protected $validUntil;

    /**
     * Event
     *
     * @var Event
     */
    protected $event;

    /**
     * Returns the price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Returns the date until the price is valid
     *
     * @return DateTime
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * Sets the date until the price is valil
     *
     * @param DateTime $validUntil
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;
    }

    /**
     * Returns the event
     *
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Sets the event
     *
     * @param Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }
}
