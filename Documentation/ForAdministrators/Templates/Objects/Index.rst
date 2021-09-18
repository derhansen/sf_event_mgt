.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _template_objects:

Template objects
================

The following objects are available in the different views.

Please have a look at the templates included with the extension, since they show many of the properties
of the given objects and how to use them.

Tip: You can use <f:debug>{object}</f:debug> in your template to see available properties of each object.

Plugin: Events and event-registration
-------------------------------------

List view
~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {events}

   :Description:
         An object holding all events that matched the configured demand in the plugin settings

 - :Object:
         {categories}

   :Description:
         All configured categories for the category menu (see :php:`plugin.tx_sfeventmgt.settings.categoryMenu` in the :ref:`eventplugin-settings`)

 - :Object:
         {locations}

   :Description:
         All available locations

 - :Object:
         {organisators}

   :Description:
         All available organisators

 - :Object:
         {speakers}

   :Description:
         All available speakers

 - :Object:
         {eventDemand}

   :Description:
         The eventDemand object

 - :Object:
         {overwriteDemand}

   :Description:
         The overwriteDemand object

 - :Object:
         {contentObjectData}

   :Description:
         The current content object of the plugin

 - :Object:
         {pageData}

   :Description:
         The current page data

Detail view
~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

 - :Object:
         {contentObjectData}

   :Description:
         The current content object of the plugin

 - :Object:
         {pageData}

   :Description:
         The current page data

Registration view
~~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

 - :Object:
         {contentObjectData}

   :Description:
         The current content object of the plugin

 - :Object:
         {pageData}

   :Description:
         The current page data

Calendar view
~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {events}

   :Description:
         An array holding all events for the current calendar month

 - :Object:
         {weeks}

   :Description:
         An array holding all events grouped by week and day

 - :Object:
         {categories}

   :Description:
         All configured categories for the category menu (see :php:`plugin.tx_sfeventmgt.settings.categoryMenu` in the :ref:`eventplugin-settings`)

 - :Object:
         {locations}

   :Description:
         All available locations

 - :Object:
         {organisators}

   :Description:
         All available organisators

 - :Object:
         {speakers}

   :Description:
         All available speakers

 - :Object:
         {eventDemand}

   :Description:
         The eventDemand object

 - :Object:
         {overwriteDemand}

   :Description:
         The overwriteDemand object

 - :Object:
         {currentPageId}

   :Description:
         The PID of the current page

 - :Object:
         {firstDayOfMonth}

   :Description:
         DateTime object with the first day of the current month

 - :Object:
         {previousMonthConfig}

   :Description:
         Array with date, month and year of the previous month

 - :Object:
         {nextMonthConfig}

   :Description:
         Array with date, month and year of the next month

 - :Object:
         {contentObjectData}

   :Description:
         The current content object of the plugin

 - :Object:
         {pageData}

   :Description:
         The current page data

Search view
~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
             Description:

 - :Object:
         {events}

   :Description:
         An object holding all events that matched the configured demand in the plugin settings and the given searchdemand

 - :Object:
         {categories}

   :Description:
         All available categories

 - :Object:
         {locations}

   :Description:
         All available locations

 - :Object:
         {organisators}

   :Description:
         All available organisators

 - :Object:
         {speakers}

   :Description:
         All available speakers

 - :Object:
         {searchDemand}

   :Description:
         The searchDemand object

 - :Object:
         {overwriteDemand}

   :Description:
         The overwriteDemand object

 - :Object:
         {contentObjectData}

   :Description:
         The current content object data

 - :Object:
         {pageData}

   :Description:
         The current page data

Notification views
~~~~~~~~~~~~~~~~~~

The following objects can be used in notification views

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

 - :Object:
         {registration}

   :Description:
         An object holding the given registration

 - :Object:
         {settings}

   :Description:
         An array of extension settings

 - :Object:
         {hmac}

   :Description:
         HMAC for the registration UID

 - :Object:
         {reghmac}

   :Description:
         Appended HMAC for the registration UID

E-Mail subjects
~~~~~~~~~~~~~~~

The following objects can be used in email subjects for event registration and custom notifications

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the event for a registration

 - :Object:
         {registration}

   :Description:
         An object holding the registration

Registration message views
~~~~~~~~~~~~~~~~~~~~~~~~~~

Registration message views are :php:`cancelRegistration`, :php:`confirmRegistration` and :php:`saveRegistrationResult`

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

 - :Object:
         {registration}

   :Description:
         An object holding the given registration

 - :Object:
         {failed}

   :Description:
         If the status is failed (not available in :php:`saveRegistrationResult`)

 - :Object:
         {titleKey}

   :Description:
         The key of the title to use in <f:translate> viewHelper

 - :Object:
         {messageKey}

   :Description:
         The key of the message to use in <f:translate> viewHelper

iCalendar view
~~~~~~~~~~~~~~

The iCalendar view is used to render an iCal file which can be downloaded by the user for the given event.
Please note, that the iCalendar view is a simple textfile. If you choose to extend the view, be sure that
new fields are compliant with RFC 5545 https://tools.ietf.org/html/rfc5545


.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

Plugin: Events and event-registration - FE user registrations
-------------------------------------------------------------

List view
~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {registrations}

   :Description:
         An object holding all registrations that matched the configured demand in the plugin settings
