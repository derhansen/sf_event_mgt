<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "sf_event_mgt" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

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
        $I->dontSee('Registration', 'a');
    }

    public function eventTitleShownAsTitleTag(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Expired Event (reg, cat1) ' . $this->lang);
        $I->seeInTitle('Expired Event (reg, cat1) ' . $this->lang);
    }

    public function validationWorksForSimpleEvent(AcceptanceTester $I)
    {
        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');
        $I->click('Send registration');
        $I->waitForElementVisible('#registration-submit', 1);

        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.firstname"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.lastname"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.company"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.email"]/span');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'invalid-email');
        $I->click('Send registration');
        $I->waitForElementVisible('#registration-submit', 2);
        $I->see('The given subject was not a valid email address.', '//*[@id="field-errors-registration.email"]/span');
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

        $I->waitForText('Registration successful', 5);
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
        $I->waitForElementVisible('#registration-submit', 1);

        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.firstname"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.lastname"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.company"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.email"]/span');
        $I->see('The given subject was empty.', '//*[@id="field-errors-registration.fields.2"]/span');
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
        $I->fillField(['name' => 'tx_sfeventmgt_pieventregistration[registration][fields][2]'], 'Field Value ' . $this->lang);

        $I->click('Send registration');

        $I->waitForText('Registration successful', 5);
        $I->see('Registration successful');

        $I->fetchEmails();
        $I->haveUnreadEmails();
        $I->haveNumberOfUnreadEmails(2);
        $I->openNextUnreadEmail();
        $I->seeInOpenedEmailSubject('New unconfirmed registration for event "Event (reg, regfields, cat1) ' . $this->lang . '"');
        $I->seeInOpenedEmailHtmlBody('Input field (req) ' . $this->lang . ':');
        $I->seeInOpenedEmailHtmlBody('Field Value ' . $this->lang);
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

        $I->waitForText('Registration on the waitlist successful', 5);
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

        $I->waitForText('Registration confirmed', 5);
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

        $I->waitForText('Registration confirmed', 5);
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

        $I->waitForText('My event registrations', 5);
        $I->see('My event registrations');
        $I->see('Expired Event (cat1, fe_user: user1) ' . $this->lang);
        $I->see('Expired Event (location: 1, fe_user: user1) ' . $this->lang);
        $I->see('Event Location 1 ' . $this->lang);
    }

    public function registrationWorksForEventWithMultiReg(AcceptanceTester $I)
    {
        $I->deleteAllEmails();
        $I->deleteAllEventRegistrations();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1, multireg) ' . $this->lang);
        $I->waitForText('Back to listview', 5);

        $I->see('0', '//*[@id="c2"]/div/div[12]/div[2]');
        $I->see('Registration', 'a');
        $I->click('Registration');
        $I->waitForText('Back to listview', 5);

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], 'johndoe@sfeventmgt.local');
        $I->selectOption(['id' => 'amountOfRegistrations'], '3');

        $I->click('Send registration');

        $I->waitForText('Registration successful', 5);
        $I->see('Registration successful');

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1, multireg) ' . $this->lang);
        $I->waitForText('Back to listview', 5);

        $I->see('3', '//*[@id="c2"]/div/div[12]/div[2]');
    }

    public function honeypotSpamcheckActiveByDefaultAndWorking(AcceptanceTester $I)
    {
        $I->deleteAllEmails();

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1) ' . $this->lang);

        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->seeElementInDOM('input[name="tx_sfeventmgt_pieventregistration[registration][hp2]"]');

        $I->executeJS('document.getElementsByName("tx_sfeventmgt_pieventregistration[registration][hp2]")[0].value = "spam";');

        $I->click('Send registration');

        $I->waitForText('Your form submission has been classified as spam.', 5);
        $I->see('Your form submission has been classified as spam.');
    }

    public function expectedEventUidIsSavedToRegistrationIndependentFromWebsiteLanguage(AcceptanceTester $I)
    {
        $I->deleteAllEventRegistrations();

        $emailPrefix = str_replace(['[', ']'], '', $this->lang);
        $email = 'event-uid-test-' . strtolower($emailPrefix) . '@sfeventmgt.local';
        $expectedEventUid = 2;

        $I->amOnPage($this->basePath . 'event-list-all');
        $I->click('Event (reg, cat1) ' . $this->lang);
        $I->see('Registration', 'a');
        $I->click('Registration');

        $I->fillField(['id' => 'firstname'], 'John');
        $I->fillField(['id' => 'lastname'], 'Doe');
        $I->fillField(['id' => 'company'], 'TYPO3');
        $I->fillField(['id' => 'email'], $email);

        $I->click('Send registration');

        $I->waitForText('Registration successful', 5);
        $I->see('Registration successful');

        $I->seeInDatabase('tx_sfeventmgt_domain_model_registration', ['email' => $email, 'event' => $expectedEventUid]);
    }
}
