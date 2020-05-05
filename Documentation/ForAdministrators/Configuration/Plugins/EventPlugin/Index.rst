.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../../Includes.txt


.. _eventplugin-settings:

Events and event-registration
=============================

Nearly all important settings can be made through the plugin, which override the
settings made with TypoScript. All plugin settings can also be configured with TypoScript
(use ``plugin.tx_sfeventmgt.settings.`` with the keys shown below).

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
         * Calendar

   :Key:

 - :Property:
         Display mode

   :View:
         List, Search, Calendar

   :Description:
         With this setting the plugin can be configured to show **all** events, only
         **future** or only **past events**.

         Available options

         * all
         * future
         * current_future
         * past

   :Key:
         displayMode

 - :Property:
         Show a single event record

   :View:
         Detail, Registration

   :Description:
         The detail view will show the configured event record if not event is passed to the detail or  registration
         action by parameter. Can be used to display a single event on a page without the need to link to the detail
         or registration page from a list view.

   :Key:
         singleEvent

 - :Property:
         Sort by

   :View:
         List, Search, Calendar

   :Description:
         Defines which field should be used for sorting events in the frontend. The default sorting field is
         "startdate", which can be overridden by using this setting.

   :Key:
         orderField

 - :Property:
         Sorting direction

   :View:
         List, Search, Calendar

   :Description:
         Defines the sorting direction for orderField. The default sorting direction is
         "asc", which can be overridden by using this setting.

         Possible values:

         * <empty value>
         * asc
         * desc

   :Key:
         orderDirection

 - :Property:
         Top event restriction

   :View:
         List, Search, Calendar

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
         Max records displayed

   :View:
         List, Search, Calendar

   :Description:
        The maximum number of records shown

   :Key:
        queryLimit

 - :Property:
         Category mode

   :View:
         List, Search, Calendar

   :Description:
         This setting defines, how categories are taken into account when selecting events.

         The following options are available:

         * Ignore category selection
         * Show events with selected categories (OR)
         * Show events with selected categories (AND)
         * Do NOT show events with selected categories (NOTOR)
         * Do NOT show events with selected categories (NOTAND)

   :Key:
         categoryConjunction

 - :Property:
         Category

   :View:
         List, Search, Calendar

   :Description:
         Restrict events to be shown by one or more category

   :Key:
         category

 - :Property:
         Include subcategory

   :View:
         List, Search, Calendar

   :Description:
         Includes subcategories of the selected category

   :Key:
         includeSubcategories

 - :Property:
         Location

   :View:
         List, Search, Calendar

   :Description:
         Restrict events to be shown by one location

   :Key:
         location

 - :Property:
         Organisator

   :View:
         List, Search, Calendar

   :Description:
         Restrict events to be shown by one organisator

   :Key:
         organisator

 - :Property:
         Speaker

   :View:
         List, Search, Calendar

   :Description:
         Restrict events to be shown by one speaker

   :Key:
         speaker

 - :Property:
         Record storage page

   :View:
         List, Search, Calendar

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
         * accepttc

         Note, that all fields are just checked, if they are empty or not. If the field "accepttc" (or any other
         boolean field) is included in the list of required fields, it is checked if the field value is true.

   :Key:
         registration.requiredFields

Tab additional
~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Detail Page

   :View:
         List, Registration

   :Description:
         Page, where plugin is configured to show event details

   :Key:
         detailPid

 - :Property:
         List Page

   :View:
         List, Details, Registration

   :Description:
         Page, where the listview for events is shown. Only available,
         when the plugin is configured to show event details.

   :Key:
         listPid

 - :Property:
         Registration Page

   :View:
         List, Details

   :Description:
         Page, where plugin is configured to show event registration

   :Key:
         registrationPid

 - :Property:
         Payment Page

   :View:
         Registration

   :Description:
         Page, where plugin is configured to handle payments for registration

   :Key:
         paymentPid

 - :Property:
         Restrict foreign records to storage page

   :View:
         List

   :Description:
         Categories, locations and organisators will only be loaded from the configured storage page (recursive)

   :Key:
         restrictForeignRecordsToStoragePage

 - :Property:
         Disable Override demand

   :View:
         List

   :Description:
         If set, the settings of the plugin can't be overridden by arguments in the URL.

   :Key:
         disableOverrideDemand

Tab template
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
         Template layout

   :View:
         List

   :Description:
         With this setting the plugin can be configured to show different template layouts.

         * Template layouts can be configured with Page TSConfig.
         * Template layout can be used/set by TypoScript (settings.templateLayout)

   :Key:
         templateLayout

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
         E-Mail address of emails sent to the user

   :View:
         Registration

   :Description:
         E-Mail address of emails sent to the user. This should
         be the email address of the site admin or a general information
         email address. The user will see this email address as sender.

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
         settings.notification.replyToEmail

   :Date type:
         String

   :Description:
         Reply-to email address of emails sent to the user

   :Default:
         empty

 - :Property:
         E-Mail address(es) of website admin

   :View:
         Registration

   :Description:
         E-Mail address(es) of website admin(s), who receives new/confirmed registrations. Multiple E-Mail addresses
         must be separated with a comma.

   :Key:
         notification.adminEmail

 - :Property:
         Subject of email sent to user when a new registration is created

   :View:
         Registration

   :Description:
         Subject of email sent to user when a new registration is created

   :Key:
         notification.registrationNew.userSubject

 - :Property:
         Subject of email sent to user when a new registration on the waitlist is created

   :View:
         Registration

   :Description:
         Subject of email sent to user when a new registration on the waitlist is created

   :Key:
         notification.registrationWaitlistNew.userSubject

 - :Property:
         Subject of email sent to admin when a new registration is created

   :View:
         Registration

   :Description:
         Subject of email sent to admin when a new registration is created

   :Key:
         notification.registrationNew.adminSubject

 - :Property:
         Subject of email sent to admin when a new registration on the waitlist is created

   :View:
         Registration

   :Description:
         Subject of email sent to admin when a new registration on the waitlist is created

   :Key:
         notification.registrationWaitlistNew.adminSubject

 - :Property:
         Subject of email sent to user when a registration has been confirmed

   :View:
         Registration

   :Description:
         Subject of email sent to user when a registration has been confirmed

   :Key:
         notification.registrationConfirmed.userSubject

 - :Property:
         Subject of email sent to user when a registration on the waitlist has been confirmed

   :View:
         Registration

   :Description:
         Subject of email sent to user when a registration on the waitlist has been confirmed

   :Key:
         notification.registrationWaitlistConfirmed.userSubject

 - :Property:
         Subject of email sent to admin when a registration has been confirmed

   :View:
         Registration

   :Description:
         Subject of email sent to admin when a registration has been confirmed

   :Key:
         notification.registrationConfirmed.adminSubject

 - :Property:
         Subject of email sent to admin when a registration on the waitlist has been confirmed

   :View:
         Registration

   :Description:
         Subject of email sent to admin when a registration on the waitlist has been confirmed

   :Key:
         notification.registrationWaitlistConfirmed.adminSubject

 - :Property:
         Subject of email sent to user when a registration has been cancelled

   :View:
         Registration

   :Description:
         Subject of email sent to user when a registration has been cancelled

   :Key:
         notification.registrationCancelled.userSubject

 - :Property:
         Subject of email sent to admin when a registration has been cancelled

   :View:
         Registration

   :Description:
         Subject of email sent to admin when a registration has been cancelled

   :Key:
         notification.registrationCancelled.adminSubject

Tab category menu
~~~~~~~~~~~~~~~~~

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
         Categories

   :View:
         List

   :Description:
         A subset of categories which will be shown in the category menu. If empty, all
         categories will be shown

   :Key:
         categoryMenu.categories

 - :Property:
         Include Subcategories

   :View:
         List

   :Description:
         Includes subcategories of selected categories to the category menu

   :Key:
         categoryMenu.includeSubcategories
