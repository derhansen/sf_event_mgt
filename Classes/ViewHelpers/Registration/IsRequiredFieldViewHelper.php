<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Class IsRequiredFieldViewHelper
 */
class IsRequiredFieldViewHelper extends AbstractConditionViewHelper
{
    /**
     * InitializeArguments
     */
    public function initializeArguments(): void
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
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        $result = false;
        if (isset($arguments['fieldname']) && isset($arguments['settings'])) {
            $defaultRequiredFields = ['firstname', 'lastname', 'email'];
            $requiredFields = array_map(
                'trim',
                explode(',', $arguments['settings']['registration']['requiredFields'] ?? '')
            );
            $allRequiredFields = array_merge($requiredFields, $defaultRequiredFields);
            $result = in_array($arguments['fieldname'], $allRequiredFields);
        }
        if (isset($arguments['registrationField'])) {
            $result = $arguments['registrationField']->getRequired();
        }

        return $result;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        if (static::evaluateCondition($this->arguments)) {
            return $this->renderThenChild();
        }

        return $this->renderElseChild();
    }
}
