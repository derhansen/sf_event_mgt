<?php

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
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class BeUserSessionService
{
    /**
     * The session key
     *
     * @var string
     */
    public const SESSION_KEY = 'sf_event_mgt';

    /**
     * Saves the given data to the session
     *
     * @param array $data
     */
    public function saveSessionData($data)
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
    public function getSessionDataByKey($key)
    {
        $result = null;
        $data = $this->getSessionData();
        if (is_array($data) && isset($data[$key])) {
            $result = $data[$key];
        }

        return $result;
    }

    /**
     * Returns the current Backend User
     *
     * @return mixed|BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
