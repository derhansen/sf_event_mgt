.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt

.. _what-does-it-do:

What does it do?
----------------

Event management and registration is an extension for TYPO3 CMS to manage events and registrations.

**Summary of features**

* Easy usage for editors
* Registration can be activated for each event individually
* Configurable additional fields in the registration form
* Optional registration waitlist with move up feature when a confirmed registration is cancelled
* Double opt in (optional) for event registration
* Attachments in registration emails to participant and/or admin
* iCal attachment in emails to participant
* Configurable validity of double opt in links
* Cancellation configurable for each event
* Prefill of registration fields for logged in frontend users
* Frontend plugin to show event registrations for logged in frontend users
* Backend administration module to manage events and registrations
* CSV export for all registrations of an event
* Notification module with configurable email templates to notify event participants
* Extendable with own fields through own extension
* Configurable template layouts for the listview
* Configurable category menu
* Searchview for events
* Create multiple registrations at once by a single user
* Optionally check email address of registrations for uniqueness per event
* Optional Spam-Protection with hCaptcha and reCAPTCHA
* Configurable and extendable spam checks (included honeypot, amount of links, challenge/response)
* Download of iCal file for events
* Add event to online calender (Google, Outlook, Office 365 and Yahoo)
* Uses TYPO3 system categories to structure events by category
* Price options valid until selected dates (e.g. for early bird prices)
* Payment processing after successful registration
* Configurable payment methods
* Show events using the "Insert Record" Content Element
* Flag event images for either listview, detailview or both
* Calendar view with possibility to navigate to next/previous month
* Automatic cache clearing when event has been changed in backend
* Lots of PSR-14 Events to extend the extension with own functionality

**Background**

* Based on Extbase and Fluid
* Covered with many unit and functional tests
* Actively maintained
