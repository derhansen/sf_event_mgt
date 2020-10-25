.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _psr14events:

PSR-14 Events
=============

The extensions contains many PSR-14 Events which make it possible to extend the extension with own functionality.
You can for example add own variables to all views or extend email notifications by own needs.

Please note, that there is no documentation for each PSR-14 event in detail, so you have to check each event
individually for supported properties. Generally I tried to make the events as self explaining as possible.

If you are new to PSR-14 Events, please reffer to the official TYPO3 documentation about
PSR-14 Events and Event Listeners.

https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Hooks/EventDispatcher/Index.html

The following PSR-14 Events are available:

Event Controller
----------------

* ModifyListViewVariablesEvent
* ModifyCalendarViewVariablesEvent
* ModifyDetailViewVariablesEvent
* ModifyRegistrationViewVariablesEvent
* ModifyCreateDependingRegistrationsEvent
* ModifyConfirmRegistrationViewVariablesEvent
* ModifyCancelRegistrationViewVariablesEvent
* ModifySearchViewVariablesEvent
* AfterRegistrationSavedEvent
* AfterRegistrationConfirmedEvent
* EventPidCheckFailedEvent
* WaitlistMoveUpEvent

Payment Controller
------------------
* ProcessPaymentInitializeEvent
* ProcessPaymentSuccessEvent
* ProcessPaymentFailureEvent
* ProcessPaymentCancelEvent
* ProcessPaymentNotifyEvent

Event Repository
----------------
* ModifyEventQueryConstraintsEvent

Notification Service
--------------------
* ModifyUserMessageSenderEvent
* ModifyUserMessageAttachmentsEvent
* AfterUserMessageSentEvent
* AfterAdminMessageSentEvent

Registration Service
--------------------
* AfterRegistrationMovedFromWaitlist