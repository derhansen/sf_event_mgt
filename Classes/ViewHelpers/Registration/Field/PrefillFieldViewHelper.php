<?php

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\ViewHelpers\Registration\Field;

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * PrefillField ViewHelper for registration fields
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class PrefillFieldViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('registrationField', 'object', 'The registrationField object', true);
    }

    /**
     * Returns a string to be used as prefill value for the given registration field (type=input). If the form
     * has already been submitted, the submitted value for the field is returned.
     *
     * @return string
     */
    public function render()
    {
        /** @var Field $registrationField */
        $registrationField = $this->arguments['registrationField'];
        // If mapping errors occured for form, return value that has been submitted
        $originalRequest = $this->getRequest()->getOriginalRequest();
        if ($originalRequest) {
            return $this->getFieldValueFromArguments($originalRequest->getArguments(), $registrationField->getUid());
        }

        return $registrationField->getDefaultValue();
    }

    /**
     * Returns the submitted value for the given field uid
     *
     * @param array $submittedValues
     * @param int $fieldUid
     * @return string
     */
    protected function getFieldValueFromArguments($submittedValues, $fieldUid)
    {
        $result = '';
        foreach ($submittedValues['registration']['fieldValues'] as $fieldValue) {
            if ((int)$fieldValue['field'] === $fieldUid) {
                $result = $fieldValue['value'];
            }
        }

        return $result;
    }

    /**
     * Shortcut for retrieving the request from the controller context
     *
     * @return \TYPO3\CMS\Extbase\Mvc\Request
     */
    protected function getRequest()
    {
        /** @var ControllerContext $controllerContext */
        $controllerContext = $this->renderingContext->getControllerContext();

        return $controllerContext->getRequest();
    }
}
