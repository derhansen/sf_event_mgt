<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

/**
 * BeUserSessionService
 */
class BeUserSessionService
{
    private const SESSION_KEY = 'sf_event_mgt';

    /**
     * Saves the given data to the session
     *
     * @param array $data
     */
    public function saveSessionData(array $data): void
    {
        $this->getBackendUser()->setAndSaveSessionData(self::SESSION_KEY, $data);
    }

    /**
     * Returns the session data
     *
     * @return mixed
     */
    public function getSessionData()
    {
        return $this->getBackendUser()->getSessionData(self::SESSION_KEY);
    }

    /**
     * Returns a specific value from the session data by the given key
     *
     * @param string $key
     * @return mixed|null
     */
    public function getSessionDataByKey(string $key)
    {
        $result = null;
        $data = $this->getSessionData();
        if (is_array($data) && isset($data[$key])) {
            $result = $data[$key];
        }

        return $result;
    }

    protected function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'] ?? null;
    }
}
