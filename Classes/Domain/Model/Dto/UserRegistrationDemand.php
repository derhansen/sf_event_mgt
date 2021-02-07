<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Domain\Model\Dto;

/**
 * UserRegistrationDemand
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UserRegistrationDemand extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Display mode
     *
     * @var string
     */
    protected $displayMode = 'all';

    /**
     * Storage page
     *
     * @var string
     */
    protected $storagePage;

    /**
     * Order field
     *
     * @var string
     */
    protected $orderField = '';

    /**
     * Order direction
     *
     * @var string
     */
    protected $orderDirection = '';

    /**
     * Current DateTime
     *
     * @var \DateTime
     */
    protected $currentDateTime;

    /**
     * Frontend user
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    protected $user;

    /**
     * Returns displayMode
     *
     * @return string
     */
    public function getDisplayMode()
    {
        return $this->displayMode;
    }

    /**
     * Sets displaymode
     *
     * @param string $displayMode
     */
    public function setDisplayMode($displayMode)
    {
        $this->displayMode = $displayMode;
    }

    /**
     * Sets storagePage
     *
     * @return string
     */
    public function getStoragePage()
    {
        return $this->storagePage;
    }

    /**
     * Returns storagePage
     *
     * @param string $storagePage
     */
    public function setStoragePage($storagePage)
    {
        $this->storagePage = $storagePage;
    }

    /**
     * Returns orderField
     *
     * @return string
     */
    public function getOrderField()
    {
        return $this->orderField;
    }

    /**
     * Sets orderField
     *
     * @param string $orderField
     */
    public function setOrderField($orderField)
    {
        $this->orderField = $orderField;
    }

    /**
     * Returns orderDirection
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * Sets orderDirection
     *
     * @param string $orderDirection
     */
    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * Sets the current DateTime
     *
     * @param \DateTime $currentDateTime CurrentDateTime
     */
    public function setCurrentDateTime(\DateTime $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    /**
     * Returns the current datetime
     *
     * @return \DateTime
     */
    public function getCurrentDateTime()
    {
        if ($this->currentDateTime != null) {
            return $this->currentDateTime;
        }

        return new \DateTime();
    }

    /**
     * Returns the frontend user
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Sets the frontend user
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}
