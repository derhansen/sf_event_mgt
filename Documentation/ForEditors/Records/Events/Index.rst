.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _events:

======
Events
======

Events are the main record of this extension. An event contains several fields, which can be used to
describe the event in detail.

General
~~~~~~~

The general tab is used to add general information about the event like a title, start- and enddate
and a description.

.. figure:: /Images/event-event.png
   :alt: Event general tab
   :class: with-shadow

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

.. figure:: /Images/event-additional.png
   :alt: Event additional tab
   :class: with-shadow

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
         Tax Rate

   :Description:
         The tax rate in percent for the price.

 - :Field:
         Currency

   :Description:
         The currency for the price.

 - :Field:
         Price options

   :Description:
         If the event has multiple prices of which the user must choose of while
         registering, you can define one or multiple price options. The following
         fields are availabe for price options.

         * Title
         * Desciption
         * Price
         * Date until the price is valid (selected date is included)

         The event management will automatically output the current price if
         the :php:`{event.currentPrice}` getter is used.

         Special getters:

         * :php:`{event.activePriceOptions}` - returns all valid price options
         * :php:`{event.priceOptions}` - returns all price options. Use {priceOption.isValid} to
           decide, if the price option is selectable or not.

         If an event has price options defined and registration is enabled, the user
         must chose one of the available price options in the registration
         process.

         See :ref:`Price option <priceoption>` chapter for more details.

 - :Field:
         Link

   :Description:
         A link (e.g. external link) for the event.

 - :Field:
         Program

   :Description:
         The program/schedule for the event.

 - :Field:
         Custom text

   :Description:
         A custom RTE text field. The field can e.g. be used to show, that event registration has ended.

Relations
~~~~~~~~~

The relations tab contains fields which holds relations locations, organisators and related events.

.. figure:: /Images/event-related.png
   :alt: Event related tab
   :class: with-shadow

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Location

   :Description:
         The location of the event choosen from the location records created.

 - :Field:
         Room

   :Description:
         Optional field for the room, where the event happens.

 - :Field:
         Organisator

   :Description:
         The organisator of the event choosen from the organisator records created.

 - :Field:
         Speaker

   :Description:
         One or multiple speaker of the event.

 - :Field:
         Related events

   :Description:
         One or more related events

Media
~~~~~

The media tab contains fields which holds media-data for the event.

.. figure:: /Images/event-media.png
   :alt: Event media tab
   :class: with-shadow

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Image

   :Description:
         One or more images. Each image can be configured to be shown either in the listview, the detailview or both.

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

Categories
~~~~~~~~~~

You can assign one or multiple categories to an event.

.. figure:: /Images/event-category.png
   :alt: Event category tab
   :class: with-shadow

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

Registration Options
~~~~~~~~~~~~~~~~~~~~

For each event, it is possible to enable registration and to limit the
amount of free places, so only a limited amount of people can participate to the event. It is also
possible to allow the user to create multiple registrations at once, if the field "Max. simultaneous
registrations per user" is set to a value greater than 1.

.. figure:: /Images/event-registration-options.png
   :alt: Event record (Tab: registration options)
   :class: with-shadow

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
         Allow registration until end date and time

   :Description:
         If set, it is possible to register to an event until the event end date and time is reached.

         Note, that this option has no effect, if the registration deadline is earlier than the
         event end date and time.

 - :Field:
         Registration start date

   :Description:
         If set, registration is only possible after the given date.

 - :Field:
         Registration deadline

   :Description:
         If set, registration is only possible until the given date.

 - :Field:
         Enable cancellation

   :Description:
         Option to enable cancellation of registrations for the event. If enabled, users can cancel their
         registration to the event.

 - :Field:
         Cancellation deadline

   :Description:
         If set, cancellation is only possible until the given date.

 - :Field:
         Enable automatic confirmation of event registrations

   :Description:
         If set, new registrations for the event will automatically be confirmed regardless of the global
         setting :php:`settings.registration.autoConfirmation`

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
         Enable waitlist

   :Description:
         Option to enable a waitlist for the event, if the max. amount of registrations is reached.

 - :Field:
         Enable unique email check for registration

   :Description:
         If set, email adresses of registrations are checked for uniqueness for the event.

 - :Field:
         Enable Payment

   :Description:
         If checked, a user registering for an event can select available payment options.

 - :Field:
            Restrict available payment methods

   :Description:
         If checked, the available payment methods for the event can be restricted

 - :Field:
            Selected payment methods

   :Description:
         Selected payment methods, if "Restrict available payment methods" is checked.
         Custom payment methods can be added. For documentation, please refer to the
         :ref:`developer_payment` section in the developers manual.

 - :Field:
         Notify admin

   :Description:
         When enabled, the administrator will receive an email for new event registrations (create/confirm)

 - :Field:
         Notify organisator

   :Description:
         When enabled, the organisator will receive an email for new event registrations (create/confirm). The email
         sent will use the same template as the admin email.


Registrations
~~~~~~~~~~~~~

Contains all registrations for the event. Only visible, when registration is enabled.

.. figure:: /Images/event-registrations.png
   :alt: Event record (Tab: registrations)
   :class: with-shadow

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Registrations

   :Description:
         A list of participants registered to the event.

 - :Field:
         Registrations on the waitlist

   :Description:
         A list of participants registered to the waitlist of the event. This option is only visible, when the waitlist feature is enabled for the event.

Metadata
~~~~~~~~

Contains fields related to meta tags

.. figure:: /Images/event-metadata.png
   :alt: Event record (Tab: metadata)
   :class: with-shadow

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Keywords

   :Description:
         One or multiple keywords used to output the meta tag "keywords"

 - :Field:
         Description

   :Description:
         A description used to output the meta tag "description"

 - :Field:
         Alternative title

   :Description:
         An alternative title which either can be used as meta tag "title" or
         which is is used to override the page title.

