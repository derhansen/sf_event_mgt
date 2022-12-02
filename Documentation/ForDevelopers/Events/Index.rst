.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _psr14events:

PSR-14 Events
=============

The extensions contains many PSR-14 events which make it possible to extend the extension with own functionality.
You can for example add own variables to all views or extend email notifications by own needs.

Please note, that there is no documentation for each PSR-14 event in detail, so you have to check each event
individually for supported properties. Generally I tried to make the events as self explaining as possible.

If you are new to PSR-14 events, please refer to the official TYPO3 documentation about
PSR-14 events and Event Listeners.

https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Hooks/EventDispatcher/Index.html

The following PSR-14 events are available:

Event Controller
----------------
* :php:`ModifyListViewVariablesEvent`
* :php:`ModifyCalendarViewVariablesEvent`
* :php:`ModifyDetailViewVariablesEvent`
* :php:`ModifyRegistrationViewVariablesEvent`
* :php:`ModifyCreateDependingRegistrationsEvent`
* :php:`ModifyConfirmRegistrationViewVariablesEvent`
* :php:`ModifyCancelRegistrationViewVariablesEvent`
* :php:`ModifySearchViewVariablesEvent`
* :php:`AfterRegistrationSavedEvent`
* :php:`AfterRegistrationConfirmedEvent`
* :php:`AfterRegistrationCancelledEvent`
* :php:`EventPidCheckFailedEvent`
* :php:`WaitlistMoveUpEvent`
* :php:`ProcessRedirectToPaymentEvent`

Payment Controller
------------------
* :php:`ProcessPaymentInitializeEvent`
* :php:`ProcessPaymentSuccessEvent`
* :php:`ProcessPaymentFailureEvent`
* :php:`ProcessPaymentCancelEvent`
* :php:`ProcessPaymentNotifyEvent`

Administration Controller
-------------------------
* :php:`InitAdministrationModuleTemplateEvent`

Event Repository
----------------
* :php:`ModifyEventQueryConstraintsEvent`

Notification Service
--------------------
* :php:`ModifyUserMessageSenderEvent`
* :php:`ModifyUserMessageAttachmentsEvent`
* :php:`AfterUserMessageSentEvent`
* :php:`AfterAdminMessageSentEvent`
* :php:`ModifyCustomNotificationLogEvent`

Registration Service
--------------------
* :php:`AfterRegistrationMovedFromWaitlist`
