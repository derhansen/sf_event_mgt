[![Latest Stable Version](https://poser.pugx.org/derhansen/sf_event_mgt/v/stable)](https://packagist.org/packages/derhansen/sf_event_mgt)
[![Unit- and functional tests](https://github.com/derhansen/sf_event_mgt/actions/workflows/UnitFunctionalTests.yml/badge.svg)](https://github.com/derhansen/sf_event_mgt/actions/workflows/UnitFunctionalTests.yml)
[![Unit- and functional tests](https://github.com/derhansen/sf_event_mgt/actions/workflows/UnitFunctionalTests.yml/badge.svg)](https://github.com/derhansen/sf_event_mgt/actions/workflows/UnitFunctionalTests.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/derhansen/sf_event_mgt/?branch=main)
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
* Optional registration waitlist with move up feature when a confirmed registration is cancelled
* Optional double or tripple opt-in for event registration
* Optional double or tripple opt-out for event cancellation
* Attachments in registration emails to participant and/or admin
* iCal attachment in emails to participant
* Configurable validity of double opt-in links
* Cancellation configurable for each event
* Prefill of registration fields for logged in frontend users
* Frontend plugin to show event registrations for logged in frontend users
* Backend administration module to manage events and registrations
* CSV export for all registrations of an event
* Notification module with configurable email templates to notify event participants
* Extendable with own fields through own extension
* Configurable template layouts for the listview
* Configurable category menu
* Search view for events
* Create multiple registrations at once by a single user
* Optionally check email address of registrations for uniqueness per event
* Configurable and extendable spam checks (included honeypot, amount of links, challenge/response)
* Optional Spam-Protection with hCaptcha and reCAPTCHA
* Download of iCal file for events
* Add event to online calendar (Google, Outlook, Office 365 and Yahoo)
* Uses TYPO3 system categories to structure events by category
* Price options (e.g. for early bird prices)
* Payment processing after successful registration
* Configurable payment methods
* Show events using the "Insert Record" Content Element
* Flag event images for either listview, detail view or both
* Calendar view with possibility to navigate to next/previous month and week
* Automatic cache clearing when event has been changed in backend
* Console command to delete registrations of expired registrations
* A lot of PSR-14 Events to extend the extension with own functionality

**Background**

* Based on Extbase and Fluid
* Covered with unit, functional and acceptance tests
* Actively maintained

## Documentation

The extension includes a detailed documentation in ReST format. You can view the extension manual on TYPO3 https://docs.typo3.org/p/derhansen/sf_event_mgt/master/en-us/ or use
ext:sphinx to view the documentation directly in your TYPO3 installation.

## Installation

### Installation using Composer

The recommended way to install the extension is by using [Composer](https://getcomposer.org/). In your Composer based TYPO3 project root, just do `composer require derhansen/sf_event_mgt`.

### Installation as extension from TYPO3 Extension Repository (TER)

Download and install the extension with the TYPO3 extension manager module.

## Breaking changes

This extension uses semantic versioning. You can expect, that each major version contains breaking changes
which must be migrated manually. The [documentation](https://github.com/derhansen/sf_event_mgt/blob/main/Documentation/Reference/BreakingChanges/Index.rst) contains a summary of all breaking changes including links
to the release notes for each affected version.

When doing a TYPO3 major version update where the extension is used, please make sure to follow all described
migrations regarding breaking changes.

## Versions

| Version | TYPO3     | PHP       | Support/Development                  |
|---------|-----------|-----------|--------------------------------------|
| 8.x     | 13.4      | 8.2 - 8.4 | Features, Bugfixes, Security Updates |
| 7.x     | 12.4      | 8.1 - 8.4 | Features, Bugfixes, Security Updates |
| 6.x     | 11.5      | 7.4 - 8.4 | Bugfixes, Security Updates           |
| 5.x     | 10.4      | 7.2 - 7.4 | Security Updates                     |
| 4.x     | 8.7 - 9.5 | 7.0 - 7.4 | Support dropped                      |
| 3.x     | 7.6 - 8.7 | 5.5 - 7.3 | Support dropped                      |
| 2.x     | 7.6 - 8.7 | 5.5 - 7.2 | Support dropped                      |
| 1.x     | 6.2 - 7.6 | 5.5 - 5.6 | Support dropped                      |

## Support

Free public support is available on the #ext-sf_event_mgt TYPO3 Slack Channel.
You can ask questions at https://stackoverflow.com and tag your question with `TYPO3`.

## Contributing

Please refer to the [contributing](CONTRIBUTING.md) document included in this repository.

## Screenshot

### Event backend form

![Event backend form](/Documentation/Images/event-event.png)

### Event administration module

![Event administration module](/Documentation/Images/event-admin.png)
