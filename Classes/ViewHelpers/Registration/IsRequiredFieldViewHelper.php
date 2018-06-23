<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;

class IsRequiredFieldViewHelper extends AbstractConditionViewHelper implements CompilableInterface
{
    /**
     * InitializeArguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('fieldname', 'string', 'A fieldname to be checked', false);
        $this->registerArgument('registrationField', 'object', 'A registration field record', false);
        $this->registerArgument('settings', 'array', 'The extension settings', true);
        parent::initializeArguments();
    }

    /**
     * Evaluates the condition
     *
     * @param array|null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null)
    {
        $result = false;
        if (isset($arguments['fieldname']) && isset($arguments['settings'])) {
            $defaultRequiredFields = ['firstname', 'lastname', 'email'];
            $requiredFields = array_map('trim', explode(',', $arguments['settings']['registration']['requiredFields']));
            $allRequiredFields = array_merge($requiredFields, $defaultRequiredFields);
            $result = in_array($arguments['fieldname'], $allRequiredFields);
        }
        if (isset($arguments['registrationField'])) {
            $result = $arguments['registrationField']->getRequired();
        }

        return $result;
    }
}
