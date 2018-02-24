<?php
namespace DERHANSEN\SfEventMgt\Controller;

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand;
use DERHANSEN\SfEventMgt\Utility\Page;

/**
 * UserRegistrationController
 *
 * @author Torben Hansen <derhansen@gmail.com>
 */
class UserRegistrationController extends AbstractController
{
    /**
     * Creates an user registration demand object with the given settings
     *
     * @param array $settings The settings
     *
     * @return \DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand
     */
    public function createUserRegistrationDemandObjectFromSettings(array $settings)
    {
        /** @var \DERHANSEN\SfEventMgt\Domain\Model\Dto\UserRegistrationDemand $demand */
        $demand = $this->objectManager->get(UserRegistrationDemand::class);
        $demand->setDisplayMode($settings['userRegistration']['displayMode']);
        $demand->setStoragePage(Page::extendPidListByChildren(
            $settings['userRegistration']['storagePage'],
            $settings['userRegistration']['recursive']
        ));
        $demand->setOrderField($settings['userRegistration']['orderField']);
        $demand->setOrderDirection($settings['userRegistration']['orderDirection']);
        return $demand;
    }

    /**
     * Shows a list of all registration of the current frontend user
     *
     * @return void
     */
    public function listAction()
    {
        $demand = $this->createUserRegistrationDemandObjectFromSettings($this->settings);
        $demand->setUser($this->registrationService->getCurrentFeUserObject());
        $registrations = $this->registrationRepository->findRegistrationsByUserRegistrationDemand($demand);
        $this->view->assign('registrations', $registrations);
    }
}
