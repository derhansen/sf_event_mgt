<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

/**
 * Location
 */
class Location extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Address
     *
     * @var string
     */
    protected $address = '';

    /**
     * Zip
     *
     * @var string
     */
    protected $zip = '';

    /**
     * City
     *
     * @var string
     */
    protected $city = '';

    /**
     * Country
     *
     * @var string
     */
    protected $country = '';

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Link
     *
     * @var string
     */
    protected $link = '';

    /**
     * Longitude
     *
     * @var float
     */
    protected $longitude = 0.0;

    /**
     * Latitude
     *
     * @var float
     */
    protected $latitude = 0.0;

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title The title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Returns the address
     *
     * @return string $address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets the address
     *
     * @param string $address Address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Returns the zip
     *
     * @return string $zip
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Sets the zip
     *
     * @param string $zip Zip
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
    }

    /**
     * Returns the city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Sets the city
     *
     * @param string $city City
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Returns the country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the country
     *
     * @param string $country Country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description The description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Returns link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Returns the longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Sets the the longitude
     *
     * @param float $longitude The longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns the latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Sets the latitude
     *
     * @param float $latitude The latitude
     */
    public function setLatitude($latitude)
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
