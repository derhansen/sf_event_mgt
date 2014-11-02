.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _plugin-settings:

Plugin settings
===============

Nearly all important settings can be made through the plugin, which override the
settings made with TypoScript.

Tab settings
~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :View:
         View:

   :Description:
         Description:

   :Key:
         Key:


 - :Property:
         What to display

   :View:
         All

   :Description:
         Which view should be shown on the given page.

         Available options:

         * List
         * Details
         * Registration

   :Key:

 - :Property:
         Detail pid

   :View:
         List, Registration

   :Description:
         Page, where plugin is configured to show event details

   :Key:
         detailPid

 - :Property:
         List pid

   :View:
         Details, Registration

   :Description:
         Page, where the listview for events is shown. Only available,
         when the plugin is configured to show event details.

   :Key:
         listPid

 - :Property:
         Registration pid

   :View:
         List, Details

   :Description:
         Page, where plugin is configured to show event registration

   :Key:
         registrationPid

 - :Property:
         Display mode

   :View:
         List

   :Description:
         With this setting the plugin can be configured to show **all** events, only
         **future** or only **past events**.

         Available options

         * all
         * future
         * past

   :Key:
         displayMode

 - :Property:
         Template layout

   :View:
         List

   :Description:
         With this setting the plugin can be configured to show different template layouts.
         Template layouts can be configured with Page TSConfig like shown below.::

           tx_sfeventmgt.templateLayouts {
             1 = 2 column layout
             2 = Event-Slider
           }

         Template layout can be used/set by TypoScript (settings.templateLayout)

   :Key:
         templateLayout

 - :Property:
         Top event restriction

   :View:
         List

   :Description:
         With this setting the plugin can be configured to show **only top event** events, to
         **except top events** or to ignore the top event restriction.

         Available options

         * 0 (None - ignore top event restriction)
         * 1 (Except top events)
         * 2 (Only top events)

   :Key:
         topEventRestriction

 - :Property:
         Category

   :View:
         List

   :Description:
         Restrict events to be shown be one or more category

   :Key:
         category

 - :Property:
         Record storage page

   :View:
         List

   :Description:
         One or more sysfolders, where events are stored

   :Key:
         storagePage

 - :Property:
         Comma seperated list of fieldnames, which are required.

   :View:
         Registration

   :Description:
         List of fieldnames, which are mandatory for registration. The fields
         firstname, lastname and email are always required and cannot be overridden.

         The following additional fields are available:

         * title
         * company
         * address
         * zip
         * city
         * country
         * phone
         * gender
         * dateOfBirth
         * notes

   :Key:
         registration.requiredFields

Tab notification
~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :View:
         View:

   :Description:
         Description:

   :Key:
         Key:


 - :Property:
         E-Mail address of e-mails sent to the user

   :View:
         Registration

   :Description:
         E-Mail address of e-mails sent to the user. This should
         be the e-mail address of the site admin or a general information
         e-mail address. The user will see this e-mail address as sender.

   :Key:
         notification.senderEmail

 - :Property:
         Name of the sender

   :View:
         Registration

   :Description:
         Name of the sender

   :Key:
         notification.senderName

 - :Property:
         E-Mail address of website admin

   :View:
         Registration

   :Description:
         E-Mail address of website admin, who receives new/confirmed registrations

   :Key:
         notification.adminEmail

 - :Property:
         Subject of e-mail sent to user when a new registration is created

   :View:
         Registration

   :Description:
         Subject of e-mail sent to user when a new registration is created

   :Key:
         notification.registrationNew.userSubject

 - :Property:
         Subject of e-mail sent to admin when a new registration is created

   :View:
         Registration

   :Description:
         Subject of e-mail sent to admin when a new registration is created

   :Key:
         notification.registrationNew.adminSubject

 - :Property:
         Subject of e-mail sent to user when a registration has been confirmed

   :View:
         Registration

   :Description:
         Subject of e-mail sent to user when a registration has been confirmed

   :Key:
         notification.registrationConfirmed.userSubject

 - :Property:
         Subject of e-mail sent to admin when a registration has been confirmed

   :View:
         Registration

   :Description:
         Subject of e-mail sent to admin when a registration has been confirmed

   :Key:
         notification.registrationConfirmed.adminSubject

