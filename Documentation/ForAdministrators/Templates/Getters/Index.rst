.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _getters:

===============
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
         :html:`{event.registrationPossible}`

   :Description:
         Returns, if registration for the event is possible. This getter respects if registration is enabled,
         if max. participants is configured/reached, if registration deadline is configured/reached and if
         registration deadline / event startdate is reached.

 - :Object:
         :html:`{event.freePlaces}`

   :Description:
         Returns the amount of free places for an event

 - :Object:
         :html:`{event.activePriceOptions}`

   :Description:
         Returns all active price options sorted by date ASC

 - :Object:
         :html:`{event.currentPrice}`

   :Description:
         Returns the current price of the event respecting possible price options

 - :Object:
         :html:`{event.cancellationPossible}`

   :Description:
         Returns, if cancellation for registrations of the event is possible

 - :Object:
         :html:`{event.registrationFieldsUids}`

   :Description:
         Returns an array with registration field uids

 - :Object:
         :html:`{event.registrations}`

   :Description:
         Special getter to return the amount of registrations that are saved to default language

 - :Object:
         :html:`{event.registrationsWaitlist}`

   :Description:
         Special getter to return the amount of waitlist registrations that are saved to default language

 - :Object:
         :html:`{event.endsSameDay}`

   :Description:
         Returns if the event ends on the same day

 - :Object:
         :html:`{event.images}`

   :Description:
         Returns the same ans :html:`{event.image}`

 - :Object:
         :html:`{event.listViewImages}`

   :Description:
         Returns all images from :html:`{event.image}` that are configured to be shown in list view

 - :Object:
         :html:`{event.firstListViewImage}`

   :Description:
         Returns the first image from :html:`{event.image}` which is configured to be shown in list view

 - :Object:
         :html:`{event.detailViewImages}`

   :Description:
         Returns all images from :html:`{event.image}` that are configured to be shown in detail view

 - :Object:
         :html:`{event.firstDetailViewImage}`

   :Description:
         Returns the first image from :html:`{event.image}` which is configured to be shown in detail view


