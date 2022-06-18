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

/**
 * UserRegistrationController
 */
class UserRegistrationController extends AbstractController
{
    /**
     * Shows a list of all registration of the current frontend user
     */
    public function listAction(): void
    {
        $demand = UserRegistrationDemand::createFromSettings($this->settings);
        $demand->setUser($this->registrationService->getCurrentFeUserObject());
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        $this->view->assign('registrations', $registrations);
    }
}
