.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _events:

Events
======

Events are the main record of this extesion. An event contains several fields, which can be used to
describe the event in detail. The general tab is used to add general information about the event like
a title, start- and enddate and a description.

.. figure:: ../../Images/event-event.png
   :align: left
   :alt: Event record

General
~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Hide

   :Description:
         If checked, the event is hidden

 - :Field:
         Top event

   :Description:
         If checked, the event is considered as a top event

 - :Field:
         Title

   :Description:
         Title of the event.

 - :Field:
         Startdate

   :Description:
         Date and time, when the event starts.

 - :Field:
         Enddate

   :Description:
         Date and time, when the event ends.

 - :Field:
         Teaser

   :Description:
         The teaser for the event.

 - :Field:
         Description

   :Description:
         The description for the event.

Additional
~~~~~~~~~~

The additional tab contains additional fields for the event like price, location, organiser, link and
program/schedule.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Price

   :Description:
         A price for the event.

 - :Field:
         Currency

   :Description:
         The currency for the price.

 - :Field:
         Location

   :Description:
         The location of the event choosen from the location records created.

 - :Field:
         Organisator

   :Description:
         The organisator of the event choosen from the organisator records created.

 - :Field:
         Link

   :Description:
         A link (e.g. external link) for the event.

 - :Field:
         Program

   :Description:
         The program/schedule for the event.

Media
~~~~~

The media tab contains fields which holds media-data for the event.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
             Description:

 - :Field:
         Image

   :Description:
         One or more images.

 - :Field:
         Files

   :Description:
         One or more files.

 - :Field:
         YouTube embed code

   :Description:
         A YouTube embed code

 - :Field:
         Additional images

   :Description:
         One or more additional images (e.g. images from the event).

Category
~~~~~~~~

You can assign one or multiple categories to an event.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Category

   :Description:
         One or multiple categories for the event


Registration
~~~~~~~~~~~~

For each event, it is possible to enable registration and to limit the
amount of free places, so only a limited amount of people can participate to the event. It is also
possible to allow the user to create multiple registrations at once, if the field "Max. simultaneous
registrations per user" is set to a value greater than 1.

.. figure:: ../../Images/event-registration.png
   :align: left
   :alt: Event record (Tab: registration)

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Enable registration

   :Description:
         Option to enable registration for the event. If enabled, users can register for
         participation to the event.

 - :Field:
         Registration deadline

   :Description:
         If set, registration is only possible until the given date.

 - :Field:
         Max. participants

   :Description:
         The amount af max. participants. If the value is zero, there is no limitation.

 - :Field:
         Max. simultaneous registrations per user

   :Description:
         The amount of registrations the participant can create with one single registration. If this
         field contains a value greater than 1, a dropdown box can be shown in the registration view
         where the user can select how many registrations should be created.

 - :Field:
         Registrations

   :Description:
         A list of participants registered to the event.

Notification
~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
             Description:

     - :Field:
             Notify admin

       :Description:
             When enabled, the administrator will receive an email for new event registrations (create/confirm)

     - :Field:
             Notify organisator

       :Description:
             When enabled, the organisator will receive an email for new event registrations (create/confirm)


Access
~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Start

   :Description:
         Date/time when event should be shown automatically (by TYPO3)

 - :Field:
         Stop

   :Description:
         Date/time when event should be hidden automatically (by TYPO3)

