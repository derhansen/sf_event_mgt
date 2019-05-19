.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _getters:

Special Getters
===============

Some domain objects contain special getters which are used in templates to avoid complex fluid conditions.

Event
~~~~~

The Event-Object has the following special getters


.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Getter:

   :Description:
         Description:

 - :Object:
         {event.registrationPossible}

   :Description:
         Returns, if registration for the event is possible. This getter respects if registration is enabled,
         if max. participants is configured/reached, if registration deadline is configured/reached and if
         registration deadline / event startdate is reached.

 - :Object:
         {event.freePlaces}

   :Description:
         Returns the amount of free places for an event

 - :Object:
         {event.activePriceOptions}

   :Description:
         Returns all active price options sorted by date ASC

 - :Object:
         {event.currentPrice}

   :Description:
         Returns the current price of the event respecting possible price options

 - :Object:
         {event.cancellationPossible}

   :Description:
         Returns, if cancellation for registrations of the event is possible

 - :Object:
         {event.registrationFieldsUids}

   :Description:
         Returns an array with registration field uids

 - :Object:
         {event.registrations}

   :Description:
         Special getter to return the amount of registrations that are saved to default language

 - :Object:
         {event.registrationsWaitlist}

   :Description:
         Special getter to return the amount of waitlist registrations that are saved to default language

 - :Object:
         {event.endsSameDay}

   :Description:
         Returns if the event ends on the same day


