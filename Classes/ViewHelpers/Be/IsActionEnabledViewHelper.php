<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Be;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Abstract Record viewhelper for backend links
 *
 * @author Torben Hansen <derhansen@gmail.com>
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
     * @return string
     */
    public function render()
    {
        $action = $this->arguments['action'];
        $settings = $this->arguments['settings'];
        $result = isset($settings['enabledActions'][$action]) &&
            (int)$settings['enabledActions'][$action] === 1
            && $this->checkAccess($action);

        return $result;
    }

    /**
     * Checks, if the current backend user has sufficient table permissions to perform the given action
     *
     * @param string $action
     * @return bool
     */
    private function checkAccess(string $action)
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

    /**
     * @return mixed|\TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    private function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}
