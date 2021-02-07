<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Service;

/**
 * SettingsService
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
}
