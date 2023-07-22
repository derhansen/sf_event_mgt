.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _waitlist_moveup:

================================
Default waitlist move up process
================================

The extension contains a built in waitlist feature to allow your users to register on a waitlist for events, where
all places are taken. It is possible to automatically move up user from the waitlist either by the built in logic
in the extension or by implementing a custom logic using the PSR-14 event :php:`WaitlistMoveUpEvent`

.. note::

   The default waitlist move up process is designed to meet the most common and simple requirements to a
   waitlist move up process. If the move up process does not fulfill your needs, you have to implement an
   own process.

How does the waitlist move up process work?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The default automatic move up process is active, when the following conditions are met for the event:

* "Enable cancellation" is activated
* "Enable waitlist" is activated
* "Automatic waitlist move up" is activated

As soon as the event is fully booked, new registration will be added to the waitlist of the event.
When a registered user cancels the registration, registrations will move up from the waitlist.

Note, that the following conditions decide, if a registration will move up:

* The registration must be confirmed
* The registration must have a valid date in the field "Registration date"
* The registration with the earliest "Registration date" will move up first

As soon as a registration moved up from the waitlist, the user will recieve an email, that the registration moved up.

Things to keep in mind when using the default move up process
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

* The default move up process does not execute, when registrations are removed from the event because the confirmation dealine exceeded
* When the simultaneous registration process is used, registrations are treated seperately by the default move up process.
  This can result in, that the main registration will be moved up, but additonal may not.
* When the simultaneous registration process is used, the dependency to the main registration will automatically be
  removed. This is recommended, since the cancellation of the main registration will result in the cancellation
  of all "connected" registrations. The TypoScript setting :typoscript:`settings.waitlist.moveUp.keepMainRegistrationDependency`
  can be used to keep the dependency to the main registration.
* When the simultaneous registration process is used, moved up registrations will automatically be enabled for
  notification.
* If a payment process is activated for the event, the :php:`AfterRegistrationMovedFromWaitlist` event must manuelly be implemented
  in order to send an email with payment information (e.g. payment link) to the user who moved up.

Customizing the move up process
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If the default move up process does not fulfill your needs, you can use the PSR-14 event :php:`WaitlistMoveUpEvent`
to create a custom move up logic. Please refer to the code of the default move up process to see how a custom
logic can be implemented.

If you implement a custom move up logic and to not want the default move up process to be executed, make sure
so set :php:`$processDefaultMoveUp` to :php:`false` in your event listener.
