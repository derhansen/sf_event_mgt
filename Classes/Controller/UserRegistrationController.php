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
use TYPO3\CMS\Core\Context\Context;

class UserRegistrationController extends AbstractController
{
    public function __construct(
        protected readonly Context $context,
    ) {
    }

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
        $this->registrationService->checkRegistrationAccess($this->request, $registration);
        $this->view->assign('registration', $registration);

        return $this->htmlResponse();
    }
}
