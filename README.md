[![Latest Stable Version](https://poser.pugx.org/derhansen/sf_event_mgt/v/stable)](https://packagist.org/packages/derhansen/sf_event_mgt)
[![Build Status](https://travis-ci.org/derhansen/sf_event_mgt.svg?branch=master)](https://travis-ci.org/derhansen/sf_event_mgt)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/?branch=master)
[![StyleCI](https://styleci.io/repos/19884798/shield?branch=master)](https://styleci.io/repos/19884798)
[![Monthly Downloads](https://poser.pugx.org/derhansen/sf_event_mgt/d/monthly)](https://packagist.org/packages/derhansen/sf_event_mgt)
[![Project Status: Active – The project has reached a stable, usable state and is being actively developed.](https://www.repostatus.org/badges/latest/active.svg)](https://www.repostatus.org/#active)

Event management and registration
=================================

## What is it?

Event management and registration is an extension for TYPO3 CMS to manage events and registrations.

**Summary of features**

* Easy usage for editors
* Registration can be activated for each event individually
* Configurable additional fields in the registration form
* Optional registration waitlist
* Double opt in (optional) for event registration
* Attachments in registration e-mails to participant and/or admin
* iCal attachment in e-mails to participant
* Configurable validity of double opt in links
* Cancellation configurable for each event
* Prefill of registration fields for logged in frontend users
* Frontend plugin to show event registrations for logged in frontend users
* Backend administration module to manage events and registrations
* CSV export for all registrations of an event
* Configurable e-mail templates to notify event participants
* Extendable with own fields through own extension
* Configurable template layouts for the listview
* Configurable category menu 
* Searchview for events
* Create multiple registrations at once by a single user
* Optionally check e-mail address of registrations for uniqueness per event
* Configurable and extendable spam checks (included honeypot, amount of links, challenge/response)
* Optional Spam-Protection with reCAPTCHA
* Download of iCal file for events
* Uses TYPO3 system categories to structure events by category
* Price options valid until selected dates (e.g. for early bird prices)
* Payment processing after successful registration
* Configurable payment methods
* Show events using the "Insert Record" Content Element
* Calendar view with possibility to navigate to next/previous month
* Automatic cache clearing when event has been changed in backend
* Lots of signal slots to extend the extension with own functionality  

**Background**

* Based on Extbase and Fluid
* Covered with unit and functional tests
* Actively maintained

## Documentation

The extension includes a detailed documentation in ReST format. You can view the extension manual on TYPO3 https://docs.typo3.org/p/derhansen/sf_event_mgt/master/en-us/ or use
ext:sphinx to view the documentation directly in your TYPO3 installation.

## Installation

### Installation using Composer

The recommended way to install the extension is by using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project root, just do `composer require derhansen/sf_event_mgt`. 

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 extension manager module.

## Contributing

Please refer to the [contributing](CONTRIBUTING.md) document included in this repository.  

## Screenshot

### Event backend form

![Event backend form](/Documentation/Images/event-event.png)

### Event administration module

![Event administration module](/Documentation/Images/event-admin.png)
