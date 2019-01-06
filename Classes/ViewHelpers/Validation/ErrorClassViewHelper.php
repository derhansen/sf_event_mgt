<?php
namespace DERHANSEN\SfEventMgt\ViewHelpers\Validation;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Registration\Field;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to render a given class when a field has validation errors
 */
class ErrorClassViewHelper extends AbstractViewHelper
{
    /**
     * Initialize arguments
     */
    public function initializeArguments()
    {
        $this->registerArgument('fieldname', 'string', 'A fieldname to be checked', false);
        $this->registerArgument('registrationField', 'object', 'A registration field record', false);
        $this->registerArgument('class', 'string', 'Classname if the field has an error', false, 'error-class');
        parent::initializeArguments();
    }

    /**
     * @return string
     */
    public function render()
    {
        $result = '';
        $validationErrors = $this->getValidationErrors();

        if (isset($this->arguments['fieldname'])) {
            $fieldname = 'registration.' . $this->arguments['fieldname'];
        } elseif (isset($this->arguments['registrationField']) &&
            $this->arguments['registrationField'] instanceof Field) {
            $fieldname = 'registration.fields.' . $this->arguments['registrationField']->getUid();
        } else {
            return '';
        }

        foreach ($validationErrors as $validationFieldName => $fieldValidationErrors) {
            if ($validationFieldName === $fieldname) {
                $result = $this->arguments['class'];
                break;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getValidationErrors()
    {
        $validationResults = $this->renderingContext->getControllerContext()
            ->getRequest()->getOriginalRequestMappingResults();

        return $validationResults->getFlattenedErrors();
    }
}
