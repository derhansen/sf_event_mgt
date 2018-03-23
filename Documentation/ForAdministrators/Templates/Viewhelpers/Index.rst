.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _viewhelpers:

Viewhelpers
===========

The following viewhelpers can be used in you templates.

PrefillViewHelper
~~~~~~~~~~~~~~~~~

This viewhelper prefills fields in the registration form with values from fe_users.

.. t3-field-list-table::
 :header-rows: 1

 - :Name:
         Name:

   :Type:
         Type:

   :Description:
         Description:

   :Default value:
         Default value:

 - :Name:
         fieldname

   :Type:
         String

   :Description:
         The fieldname which should be prefilled

   :Default value:


 - :Name:
         prefillSettings

   :Type:
         Array

   :Description:
         Array of fieldname mappings to fe_users

         **Example**::

           prefillFields {
             firstname = first_name
             lastname = last_name
             customfields = custom_field_feuser
           }

   :Default value:
         Empty array


Uri.PageViewhelper
~~~~~~~~~~~~~~~~~~

This viewhelper does exactly the same as f:uri.page, but this viewhelper
builds frontend links with buildFrontendUri, so links to FE pages can get
generated in the TYPO3 backend.

This viewhelper can be used in e-mail templates for custom notifications, when you want to link to a
given page in you TYPO3 website.

Usage: http://docs.typo3.org/typo3cms/ExtbaseGuide/stable/Fluid/ViewHelper/Uri/Page.html

**Example**::

  <e:uri.page pageUid="4" additionalParams="{tx_sfeventmgt_pievent:{event: registration.event, action: 'detail', controller: 'Event'}}" absolute="1"/>


Event.SimultaneousRegistrationsViewHelper
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This viewhelper renders an array of possible simultaneous registration for
the given event. The viewhelper respects the max. amount of simultaneous
registrations per user and also respects the amount of remaining participants
for the event.

The index of the array returned starts with 1, so the resulting array can be used
directly in the f:form.select viewhelper.

**Example**::

  <f:form.select id="amountOfRegistrations" property="amountOfRegistrations" options="{e:event.simultaneousRegistrations(event: '{event}')}" />


Format.ICalendarDateViewHelper
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Formats the given DateTime object according to rfc5545, so it can be used in the iCalendar view

Format.ICalendarDescriptionViewHelper
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Formats the given string according to rfc5545, so it can be used in the iCalendar view

Registration.Hmac
~~~~~~~~~~~~~~~~~

Must be used, when the plugin :ref:`userregistrationplugin-settings` is used and it should be possible
for users to cancel registrations (if configured in event). See usage in UserRegistration templates.

Title
~~~~~

Use this viewhelper to set the page title and indexed search title on event-detail and -registration pages.

**Example**::

  <e:title pageTitle="{event.title}" indexedDocTitle="A custom title for indexed search"/>