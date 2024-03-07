<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace DERHANSEN\SfEventMgt\Controller;

use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Domain\Model\Registration;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\PropagateResponseException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\ErrorController;

class UserRegistrationController extends AbstractController
{
    /**
     * Shows a list of all registration of the current frontend user
     */
    public function listAction(): ResponseInterface
    {
        $demand = UserRegistrationDemand::createFromSettings($this->settings);
        $demand->setUser($this->registrationService->getCurrentFeUserObject());
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        $this->view->assign('registrations', $registrations);

        return $this->htmlResponse();
    }

    /**
     * Shows a detail page for the given registration
     */
    public function detailAction(Registration $registration): ResponseInterface
    {
        $this->checkRegistrationAccess($registration);
        $this->view->assign('registration', $registration);

        return $this->htmlResponse();
    }

    /**
     * Checks, if the given registration belongs to the current logged in frontend user. If not, a
     * page not found response is thrown.
     */
    public function checkRegistrationAccess(Registration $registration): void
    {
        if (!$this->getFrontendUser()->user ||
            !$registration->getFeUser() ||
            $this->getFrontendUser()->user['uid'] !== (int)$registration->getFeUser()->getUid()) {
            $response = GeneralUtility::makeInstance(ErrorController::class)->pageNotFoundAction(
                $this->request,
                'Registration not found.'
            );
            throw new PropagateResponseException($response, 1671627320);
        }
    }

    protected function getFrontendUser(): FrontendUserAuthentication
    {
        return $this->getTypoScriptFrontendController()->fe_user;
    }
}
