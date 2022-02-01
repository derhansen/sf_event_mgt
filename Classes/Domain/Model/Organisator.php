<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model;

/**
 * Organisator
 *
 * @author AlexPixelant
 */
class Organisator extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Name of Organisator
     *
     * @var string
     */
    protected $name = '';

    /**
     * E-Mail of Organisator
     *
     * @var string
     */
    protected $email = '';

    /**
     * E-Mail signature of Organisator
     *
     * @var string
     */
    protected $emailSignature = '';

    /**
     * Phone number of Organisator
     *
     * @var string
     */
    protected $phone = '';

    /**
     * URL of Organisator
     *
     * @var string
     */
    protected $www = '';

    /**
     * Image of Organisator
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image;

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name The name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email The email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmailSignature(): string
    {
        return $this->emailSignature;
    }

    /**
     * @param string $emailSignature
     */
    public function setEmailSignature(string $emailSignature)
    {
        $this->emailSignature = $emailSignature;
    }

    /**
     * Returns the phone
     *
     * @return string $phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Sets the phone
     *
     * @param string $phone The phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Returns the www
     *
     * @return string $www
     */
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Sets the www
     *
     * @param string $www The www
     */
    public function setWww($www)
    {
        $this->www = $www;
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image The image
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }
}
