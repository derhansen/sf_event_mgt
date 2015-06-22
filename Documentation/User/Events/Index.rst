.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _events:

Events
======

Events are the main record of this extesion. An event contains several fields, which can be used to
describe the event in detail. For each event, it is possible to enable registration and to limit the
amount of free places, so only a limited amount of people can participate to the event. It is
possible to allow the user to create multiple registrations at once, if the field "Max. simultaneous
registrations per user" is set to a value greater than 1.

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
         Title

   :Description:
         Title of the event.

 - :Field:
         Teaser

   :Description:
         The teaser for the event.

 - :Field:
         Description

   :Description:
         The description for the event.

 - :Field:
         Link

   :Description:
         A link (e.g. external link) for the event.

 - :Field:
         Top event

   :Description:
         If checked, the event is considered as a top event

 - :Field:
         Startdate

   :Description:
         Date and time, when the event starts.

 - :Field:
         Enddate

   :Description:
         Date and time, when the event ends.

 - :Field:
         Location

   :Description:
         The location of the event choosen from the location records created.

 - :Field:
         Price

   :Description:
         A price for the event.

 - :Field:
         Currency

   :Description:
         The currency for the price.

 - :Field:
         Category

   :Description:
         One or more categories.

Media
~~~~~

.. t3-field-list-table::
 :header-rows: 1

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

.. figure:: ../../Images/event-registration.png
   :align: left
   :alt: Event record (Tab: registration)


Registration
~~~~~~~~~~~~

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

