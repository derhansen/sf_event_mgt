<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
    /**
     * Deletes all event registrations from the database
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function deleteAllEventRegistrations()
    {
        $driver = $this->getModule('Db')->_getDriver();
        $driver->executeQuery('DELETE FROM tx_sfeventmgt_domain_model_registration WHERE uid > 2;', []);
        $driver->executeQuery('DELETE FROM tx_sfeventmgt_domain_model_registration_fieldvalue;', []);
        $driver->executeQuery('DELETE FROM cache_pages;', []);
    }

    /**
     * **HOOK** executed before suite
     *
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->deleteAllEventRegistrations();
    }
}
