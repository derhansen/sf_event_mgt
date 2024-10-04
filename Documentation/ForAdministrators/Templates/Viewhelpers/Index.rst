.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _viewhelpers:

===========
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

This viewhelper does generates a link to the given page with the given
arguments.

This viewhelper can be used in email templates for custom notifications, when you want to link to a
given page in you TYPO3 website.

.. note::

   This ViewHelper is not aware of any extbase context, so arguments must be
   fully resolved (e.g. use :php:`{event.uid}` if an action required the
   :php:`event` argument)

**Example 1**::

  <e:uri.page pageUid="22" additionalParams="{tx_sfeventmgt_pieventdetail:{event: registration.event.uid, action: 'detail', controller: 'Event'}}" />

**Example 2**::

  <a href="{e:uri.page(
      pageUid: 123,
      additionalParams: '{tx_sfeventmgt_pieventregistration: {pluginName:\'Pieventregistration\',extensionName:\'sfeventmgt\',action:\'cancelRegistration\',controller:\'Event\',reguid: registration.uid, hmac: hmac}}',
      absolute: 1,
  )}" class="button">Remove registration</a>

Uri.OnlineCalendar
~~~~~~~~~~~~~~~~~~

This viewhelper renders a link which will add the given event to an online
calendar of either Google, Outlook, Office 365 or Yahoo.

Available types:

* google
* outlook
* office365
* yahoo

**Example**::

  <e:uri.onlineCalendar type="google" event="{event}" />


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

Registration.IsRequiredField
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Can be used to show content, if a field or registration field is configured as required.
See usage in Registration template and registration field partials.

**Example**::

  <e:registration.isRequiredField settings="{settings}" fieldname="lastname">
      <span class="event-required">*</span>
  </e:registration.isRequiredField>

Validation.ErrorClass
~~~~~~~~~~~~~~~~~~~~~

Can be used to show a string, when a given field or registration field has validation errors
See usage in Registration template and registration field partials.

**Example**::

  <e:validation.errorClass fieldname="email" class="my-custom-class" />

Title
~~~~~

Use this viewhelper to set the page title and indexed search title on event-detail and -registration pages.

**Example**::

  <e:title pageTitle="{event.title}" indexedDocTitle="A custom title for indexed search"/>

Category.Count
~~~~~~~~~~~~~~

Can be used to get the amount of events per category.

**Example**::

  <e:category.count categoryUid="{category.uid}" />

MetaTag
~~~~~~~

Use this viewhelper to add various meta tags related to the event. The default template for the event
detail view uses this viewhelper to output the meta tags "keyword", "description" and "og:title".

**Example**::

  <e:metaTag name="keywords" content="{event.metaKeywords}" />

