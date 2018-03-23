<?php
namespace DERHANSEN\SfEventMgt\Service;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

/**
 * SettingsService
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class SettingsService
{
    /**
     * Returns an array (key-value pair) of custom notifications that can be used in
     * select boxes
     *
     * @param array $settings Settings
     *
     * @return array
     */
    public function getCustomNotifications($settings)
    {
        if (!is_array($settings['notification']['customNotifications'])) {
            return [];
        }
        $notifications = [];
        foreach ($settings['notification']['customNotifications'] as $notificationKey => $notificationValue) {
            $notifications[$notificationKey] = $notificationValue['title'];
        }

        return $notifications;
    }

    /**
     * Returns an array of page uids for which the cache should be cleared
     *
     * @param array $settings Settings
     *
     * @return array
     */
    public function getClearCacheUids($settings)
    {
        $clearCacheUids = $settings['clearCacheUids'];

        if (is_int($settings['detailPid'])) {
            $clearCacheUids .= ',' . $settings['detailPid'];
        }

        if (is_int($settings['listPid'])) {
            $clearCacheUids .= ',' . $settings['listPid'];
        }

        if ($clearCacheUids == null) {
            return [];
        }
        $return = preg_split('/,/', $clearCacheUids, null, PREG_SPLIT_NO_EMPTY);

        return $return;
    }
}
