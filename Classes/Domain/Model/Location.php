<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Location
 */
class Location extends AbstractEntity
{
    protected string $title = '';
    protected string $address = '';
    protected string $zip = '';
    protected string $city = '';
    protected string $country = '';
    protected string $description = '';
    protected string $link = '';
    protected float $longitude = 0.0;
    protected float $latitude = 0.0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip)
    {
        $this->zip = $zip;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link)
    {
        $this->link = $link;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Special getter to return the full address of the location
     *
     * @param string $separator
     * @return string
     */
    public function getFullAddress(string $separator = '<br/>'): string
    {
        $locationData = [];
        $locationData[] = $this->getTitle();
        $locationData[] = $this->getAddress();
        $locationData[] = trim($this->getZip() . ' ' . $this->getCity());
        $locationData[] = $this->getCountry();
        $locationData = array_filter(
            $locationData,
            function ($value) {
                return str_replace(' ', '', $value) !== '';
            }
        );
        return implode($separator, $locationData);
    }
}
