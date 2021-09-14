<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Be;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Abstract Record viewhelper for backend links
 */
class IsActionEnabledViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('action', 'string', 'Name of the action', true);
        $this->registerArgument('settings', 'array', 'Settings for backend module', true);
    }

    /**
     * Renders a edit link for the given Event UID
     *
     * @return bool
     */
    public function render(): bool
    {
        $action = $this->arguments['action'];
        $settings = $this->arguments['settings'];
        return isset($settings['enabledActions'][$action]) &&
            (int)$settings['enabledActions'][$action] === 1
            && $this->checkAccess($action);
    }

    /**
     * Checks, if the current backend user has sufficient table permissions to perform the given action
     *
     * @param string $action
     * @return bool
     */
    private function checkAccess(string $action): bool
    {
        $result = false;
        switch ($action) {
            case 'notify':
                $result = $this->getBackendUser()->check(
                    'tables_select',
                    'tx_sfeventmgt_domain_model_customnotificationlog'
                );
                break;
            case 'export':
                $result = $this->getBackendUser()->check('tables_select', 'tx_sfeventmgt_domain_model_registration') &&
                    $this->getBackendUser()->check(
                        'tables_select',
                        'tx_sfeventmgt_domain_model_registration_field'
                    ) &&
                    $this->getBackendUser()->check(
                        'tables_select',
                        'tx_sfeventmgt_domain_model_registration_fieldvalue'
                    );
                break;
            default:
        }

        return $result;
    }

    private function getBackendUser(): ?BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'] ?? null;
    }
}
