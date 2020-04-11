<?php
namespace DERHANSEN\SfEventMgt\Tests\Acceptance;

use AcceptanceTester;

/**
 * Class AbstractEventsTests
 */
class AbstractEventsTests
{
    protected $basePath = '';
    protected $lang = '';

    public function listsAllEvents(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->see('Category 1 ' . $this->lang);
        $I->see('Category 2 ' . $this->lang);
        $I->see('Category 3 ' . $this->lang);
        $I->see('Category 4 ' . $this->lang);
        $I->see('Expired Event (reg, cat1) ' . $this->lang);
    }

    public function categoryMenuForAllCategories(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->see('Category 1 ' . $this->lang);
        $I->see('Category 2 ' . $this->lang);
        $I->see('Category 3 ' . $this->lang);
        $I->see('Category 4 ' . $this->lang);
        $I->canSeeNumberOfElementsInDOM('.button.button-outline', 4);
    }

    public function categoryMenuForLimitedCategories(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-category-menu');
        $I->see('Category 1 ' . $this->lang);
        $I->see('Category 2 ' . $this->lang);
        $I->canSeeNumberOfElementsInDOM('.button.button-outline', 2);
    }

    public function category1FilterDoesNotShowCategory2Events(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Category 1 ' . $this->lang);
        $I->dontSee('cat2');
    }

    public function registrationForExpiredEventNotPossible(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Expired Event (reg, cat1) ' . $this->lang);
        $I->seeInTitle('Expired Event (reg, cat1) ' . $this->lang);
    }

    public function eventTitleShownAsTitleTag(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Expired Event (reg, cat1) ' . $this->lang);
        $I->dontSee('Registration', 'a');
    }

    public function validationWorksForSimpleEvent(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');
        $I->click('Send registration');
        // Firstname (2), Lastname (3), Company (5) and Email (10)
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[2]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[3]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[5]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[10]/div/span');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'invalid-email');
        $I->click('Send registration');
        $I->see('The given subject was not a valid email address.', '//*[@id="c3"]/div/form/fieldset/div[10]/div/span');
    }

    public function registrationWorksForSimpleEvent(AcceptanceTester $I)
    {
        $I->deleteAllEmails();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');

        $I->click('Send registration');

        $I->see('Registration successful');

        $I->fetchEmails();
        $I->haveUnreadEmails();
        $I->haveNumberOfUnreadEmails(2);
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('New unconfirmed registration for event "Event (reg, cat1) ' . $this->lang . '"');
        $I->seeInOpenedEmailRecipients('admin@sfeventmgt.local');
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('Your registration for event "Event (reg, cat1) ' . $this->lang . '"');
        $I->seeInOpenedEmailRecipients('johndoe@sfeventmgt.local');
    }

    public function validationWorksForEventWithRegFields(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, regfields, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');
        $I->click('Send registration');
        // Firstname (2), Lastname (3), Company (5), Email (10), Input field (req) [LANG] (15)
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[2]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[3]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[5]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[10]/div/span');
        $I->see('The given subject was empty.', '//*[@id="c3"]/div/form/fieldset/div[15]/div/span');
    }

    public function registrationWorksForEventWithRegFields(AcceptanceTester $I)
    {
        $I->deleteAllEmails();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, regfields, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');
        $I->fillField(['name' => 'tx_sfeventmgt_pievent[registration][fields][2]'], 'Field Value ' . $this->lang);

        $I->click('Send registration');

        $I->see('Registration successful');

        $I->fetchEmails();
        $I->haveUnreadEmails();
        $I->haveNumberOfUnreadEmails(2);
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('New unconfirmed registration for event "Event (reg, regfields, cat1) ' . $this->lang . '"');
        $I->seeInOpenedEmailBody('Input field (req) ' . $this->lang . ':');
        $I->seeInOpenedEmailBody('Field Value ' . $this->lang);
        $I->seeInOpenedEmailRecipients('admin@sfeventmgt.local');
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('Your registration for event "Event (reg, regfields, cat1) ' . $this->lang . '"');
        $I->seeInOpenedEmailRecipients('johndoe@sfeventmgt.local');
    }

    public function registrationForFullyBookedEventNotPossible(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event fully booked (reg, cat1) ' . $this->lang);
        $I->dontSee('Registration', 'a');
    }

    public function registrationWorksForWaitlistEvent(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event fully booked waitlist (reg, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');
        $I->see('Please note, that the event is fully booked and you are registering to the waitlist');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');

        $I->click('Send registration');

        $I->see('Registration on the waitlist successful');

        $I->fetchEmails();
        $I->haveUnreadEmails();
        $I->haveNumberOfUnreadEmails(2);
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('New unconfirmed registration for event "Event fully booked waitlist (reg, cat1) ' . $this->lang . '" on the waitlist');
        $I->seeInOpenedEmailRecipients('admin@sfeventmgt.local');
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('Your registration for event "Event fully booked waitlist (reg, cat1) ' . $this->lang . '" on the waitlist');
        $I->seeInOpenedEmailRecipients('johndoe@sfeventmgt.local');
    }

    public function registrationWorksForSimpleEventWithAutoConfirmation(AcceptanceTester $I)
    {
        $I->deleteAllEmails();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1, autoconfirm) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');

        $I->click('Send registration');

        $I->see('Registration confirmed');

        $I->fetchEmails();
        $I->haveUnreadEmails();
        $I->haveNumberOfUnreadEmails(2);
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('Registration for "Event (reg, cat1, autoconfirm) ' . $this->lang . '" confirmed');
        $I->seeInOpenedEmailRecipients('admin@sfeventmgt.local');
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('Registration for event "Event (reg, cat1, autoconfirm) ' . $this->lang . '" successful');
        $I->seeInOpenedEmailRecipients('johndoe@sfeventmgt.local');
    }

    public function registrationIncreasesRegistrationCount(AcceptanceTester $I)
    {
        $I->deleteAllEventRegistrations();
        $I->deleteAllEmails();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat3, autoconfirm) ' . $this->lang);
        $I->see('0', '//*[@id="c2"]/div/div[12]/div[2]');

        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');

        $I->click('Send registration');

        $I->see('Registration confirmed');

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat3, autoconfirm) ' . $this->lang);
        $I->see('1', '//*[@id="c2"]/div/div[12]/div[2]');
    }

    public function eventTitleTranslatedInUserRegistration(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'login');
        $I->fillField(['name' => 'user'], 'user1');
        $I->fillField(['name' => 'pass'], '123456');
        $I->click('Login');

        $I->see('My event registrations');
        $I->see('Expired Event (cat1, fe_user: user1) ' . $this->lang);
    }
}
