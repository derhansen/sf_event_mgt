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
use DERHANSEN\SfEventMgt\Domain\Model\FrontendUser;
use DERHANSEN\SfEventMgt\Utility\PageUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * UserRegistrationDemand
 */
class UserRegistrationDemand
{
    protected string $displayMode = 'all';
    protected string $storagePage = '';
    protected string $orderField = '';
    protected string $orderDirection = '';
    protected ?DateTime $currentDateTime = null;
    protected ?FrontendUser $user = null;

    public function getDisplayMode(): string
    {
        return $this->displayMode;
    }

    public function setDisplayMode(string $displayMode): void
    {
        $this->displayMode = $displayMode;
    }

    public function getStoragePage(): string
    {
        return $this->storagePage;
    }

    public function setStoragePage(string $storagePage): void
    {
        $this->storagePage = $storagePage;
    }

    public function getOrderField(): string
    {
        return $this->orderField;
    }

    public function setOrderField(string $orderField): void
    {
        $this->orderField = $orderField;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    public function setOrderDirection(string $orderDirection): void
    {
        $this->orderDirection = $orderDirection;
    }

    public function setCurrentDateTime(DateTime $currentDateTime): void
    {
        $this->currentDateTime = $currentDateTime;
    }

    public function getCurrentDateTime(): DateTime
    {
        if ($this->currentDateTime != null) {
            return $this->currentDateTime;
        }

        return new \DateTime();
    }

    public function getUser(): ?FrontendUser
    {
        return $this->user;
    }

    public function setUser(?FrontendUser $user): void
    {
        $this->user = $user;
    }

    /**
     * Creates a new UserRegistrationDemand object from the given settings. Respects recursive setting for storage page
     * and extends all PIDs to children if set.
     *
     * @param array $settings
     * @return UserRegistrationDemand
     */
    public static function createFromSettings(array $settings = []): self
    {
        $demand = GeneralUtility::makeInstance(UserRegistrationDemand::class);
        $demand->setDisplayMode($settings['userRegistration']['displayMode'] ?? 'all');
        $demand->setStoragePage(
            PageUtility::extendPidListByChildren(
                (string)($settings['userRegistration']['storagePage'] ?? ''),
                (int)($settings['userRegistration']['recursive'] ?? 0)
            )
        );
        $demand->setOrderField($settings['userRegistration']['orderField'] ?? '');
        $demand->setOrderDirection($settings['userRegistration']['orderDirection'] ?? '');

        return $demand;
    }
}
