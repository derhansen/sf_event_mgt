﻿.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _tsref:

TypoScript reference
====================

More TypoScript settings are available on the :ref:`eventplugin-settings` page. Also make sure to check the included
:php:`setup.txt` file for available/default TypoScript settings.

plugin.tx_sfeventmgt
~~~~~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Date type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:

 - :Property:
         view.templateRootPath

   :Date type:
         String

   :Description:
         Path to the templates. The default setting is EXT:sf_event_mgt/Resources/Private/Templates/

   :Default:
         **Extbase default**

 - :Property:
         view.partialRootPath

   :Date type:
         String

   :Description:
         Path to the partials. The default setting is EXT:sf_event_mgt/Resources/Private/Partials/

   :Default:
         **Extbase default**

 - :Property:
         view.layoutRootPath

   :Date type:
         String

   :Description:
         Path to the layouts. The default setting is EXT:sf_event_mgt/Resources/Private/Layouts/

   :Default:
         **Extbase default**

 - :Property:
         settings.calendar.firstDayOfWeek

   :Date type:
         Integer

   :Description:
         First day of week 0 (sunday) bis 6 (saturday). The default value "1" is set to monday.

   :Default:
         1

 - :Property:
         settings.calendar.includeEventsForEveryDayOfAllCalendarWeeks

   :Date type:
         Boolean

   :Description:
         If set, the calendar will show events for all days of all shown weeks of the calendar and not only
         events for the current month.

   :Default:
         1 (true)

 - :Property:
         settings.calendar.showWeekNumber

   :Date type:
         Boolean

   :Description:
         Definies, if the calendar should show week numbers or not.

   :Default:
         1 (true)

 - :Property:
         settings.detail.checkPidOfEventRecord

   :Date type:
         Boolen

   :Description:
         If set, the detail view checks the incoming event record against the defined starting point(s).
         If those don’t match, the event record won’t be displayed.

   :Default:
         0 (False)

 - :Property:
         settings.detail.imageWidth

   :Date type:
         Integer

   :Description:
         Default width of images in detail view

   :Default:
         200

 - :Property:
         settings.detail.imageHeight

   :Date type:
         Integer

   :Description:
         Default height of images in detail view

   :Default:
         Empty

 - :Property:
         settings.detail.isShortcut

   :Date type:
         Boolean

   :Description:
         This setting should be set to "1" if the event should be fetched from the Content Object data.
         This option should only be set to "1", if events are displayed using the "Insert Record" content element

   :Default:
         0 (false)

 - :Property:
         settings.registration.checkPidOfEventRecord

   :Date type:
         Boolen

   :Description:
         If set, the registration view checks the incoming event record against the defined starting point(s).
         If those don’t match, the registration to the event is not possible.

   :Default:
         0 (False)

 - :Property:
         settings.registration.autoConfirmation

   :Date type:
         Boolean

   :Description:
         If set to true, new registration will automatically be confirmed by redirecting
         the user to the confirmRegistration-Action.

   :Default:
         0 (false)

 - :Property:
         settings.registration.deleteExpiredRegistrations

   :Date type:
         Boolean

   :Description:
         If set to true, expired registrations will be deleted by the action in the backend module. If this
         setting is set to false, expired registrations will just be set to **hidden**

         Note, this setting has no effect for the `cleanup` CLI command.

   :Default:
         0 (false)

 - :Property:
         settings.registration.formatDateOfBirth

   :Date type:
         string

   :Description:
         Date format of field dateOfBirth

   :Default:
         d.m.Y

 - :Property:
         settings.registration.requiredFields

   :Date type:
         String

   :Description:
         List of required fields in registration. The fields firstname, lastname and email
         are always required and cannot be overridden.

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
         * captcha

         Note, that all fields are just checked, if they are empty or not. If the field "accepttc" (or any other
         boolean field) is included in the list of required fields, it is checked if the field value is true.

   :Default:
         empty

 - :Property:
         settings.registration.linkTermsAndConditions

   :Date type:
         String

   :Description:
         A page or an external URL that can be used in the registration template to show "Terms & Conditions"

   :Default:
         empty

 - :Property:
         settings.registration.prefillFields.{fieldname}

   :Date type:
         String

   :Description:
         Key/value mapping for prefilling fields from fe_users table. The
         key-field is the fieldname in sf_event_mgt and the value-field is
         the fieldname in fe_users.

   :Default:

         * firstname = first_name
         * lastname = last_name
         * address = address
         * zip = zip
         * city = city
         * country = country
         * email = email
         * phone = telephone

 - :Property:
         settings.waitlist.moveUp.keepMainRegistrationDependency

   :Date type:
         Boolean

   :Description:
         If set to true (1), a registration will keep the dependency to the main registration if the registration
         has been submitted using the simultaneous registration process. Note, that it is recommended to set this
         value to false (0), since cancellation of the main registration will also cancel moved up "child"
         registrations.

   :Default:
         false,

 - :Property:
         settings.confirmation.linkValidity

   :Date type:
         Integer

   :Description:
         Validity of confirmation link in seconds

   :Default:
         3600

 - :Property:
         settings.notification.senderEmail

   :Date type:
         String

   :Description:
         E-mail address for emails sent to user

   :Default:
         empty

 - :Property:
         settings.notification.senderName

   :Date type:
         String

   :Description:
         Sender name for emails sent to user

   :Default:
         empty

 - :Property:
         settings.notification.replyToEmail

   :Date type:
         String

   :Description:
         Reply-to email address of emails sent to the user

   :Default:
         empty

 - :Property:
         settings.notification.senderSignature

   :Date type:
         String

   :Description:
         Signature shown in emails sent to user

   :Default:
         empty

 - :Property:
         settings.notification.adminEmail

   :Date type:
         String

   :Description:
         Admin E-mail address

   :Default:
         empty

 - :Property:
         settings.notification.registrationDataAsSenderForAdminEmails

   :Date type:
         Boolean

   :Description:
         If set, admin emails will be sent by the email-address and sender name (firstname and lastname)
         set in the registration

   :Default:
         false

 - :Property:
         settings.notification.disabled

   :Date type:
         Boolean

   :Description:
         If set, the email notification feature is completely disabled. This includes user and admin emails as well
         as user notifications from the backend.

         Note, that the functionality of sending Notifications in the backend module is also disabled when this option
         is set to "true"

   :Default:
         false

 - :Property:
         settings.notification.registrationNew.userSubject

   :Date type:
         String

   :Description:
         User-Subject for new registration

   :Default:
         Your event registration

 - :Property:
         settings.notification.registrationNew.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for new registration

   :Default:
         New unconfirmed event registration

 - :Property:
         settings.notification.registrationNew.attachments

   :Date type:
         String

   :Description:
         Attachment configuration for new unconfirmed event registrations. See :ref:`email-attachments`

   :Default:
         empty

 - :Property:
         settings.notification.registrationWaitlistNew.userSubject

   :Date type:
         String

   :Description:
         User-Subject for new registration on the waitlist

   :Default:
         Your event registration on the waitlist

 - :Property:
         settings.notification.registrationWaitlistNew.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for new registration on the waitlist

   :Default:
         New unconfirmed event registration on the waitlist

 - :Property:
         settings.notification.registrationWaitlistNew.attachments

   :Date type:
         String

   :Description:
         Attachment configuration for new unconfirmed event registrations on the waitlist. See :ref:`email-attachments`

   :Default:
         empty

 - :Property:
         settings.notification.registrationConfirmed.userSubject

   :Date type:
         String

   :Description:
         User-Subject for confirmed registration

   :Default:
         Event registration successful

 - :Property:
         settings.notification.registrationConfirmed.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for confirmed registration

   :Default:
         Event registration confirmed

 - :Property:
         settings.notification.registrationConfirmed.attachments

   :Date type:
         String

   :Description:
         Attachment configuration for confirmed event registrations. See :ref:`email-attachments`

   :Default:
         empty

 - :Property:
         settings.notification.registrationWaitlistConfirmed.userSubject

   :Date type:
         String

   :Description:
         User-Subject for confirmed registration on the waitlist

   :Default:
         Event registration on the waitlist successful

 - :Property:
         settings.notification.registrationWaitlistConfirmed.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for confirmed registration on the waitlist

   :Default:
         Event registration on the waitlist confirmed

 - :Property:
         settings.notification.registrationWaitlistConfirmed.attachments

   :Date type:
         String

   :Description:
         Attachment configuration for confirmed event registrations on the waitlist. See :ref:`email-attachments`

   :Default:
         empty

 - :Property:
     settings.notification.registrationCancelled.userSubject

   :Date type:
         String

   :Description:
         User-Subject for cancelled registration

   :Default:
         Event registration cancelled successful

 - :Property:
         settings.notification.registrationCancelled.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for cancelled registration

   :Default:
         Event registration cancelled

 - :Property:
         settings.search.dateFormat

   :Date type:
         String

   :Description:
         Date format for date fields in the search view

   :Default:
         Y-m-d

 - :Property:
         settings.search.fields

   :Date type:
         String

   :Description:
         Fields to be included in a query for the search view

   :Default:
         title, teaser

 - :Property:
         settings.search.adjustTime

   :Date type:
         boolean

   :Description:
         When the setting `settings.search.dateFormat` is set to a date only, it is recommended to set this option
         to true. The time for a given startdate will be set to 00:00:00 and the time for a given enddate will be set
         to 23:59:59, so all events for the given dates will be found by a search.

   :Default:
         true

 - :Property:
         settings.pagination.enablePagination

   :Date type:
         boolean

   :Description:
         If true, the list view outputs required variables to render a pagination.

   :Default:
         false

 - :Property:
         settings.pagination.itemsPerPage

   :Date type:
         integer

   :Description:
         Amount of items per paginated page.

   :Default:
         10

 - :Property:
         settings.pagination.maxNumPages

   :Date type:
         integer

   :Description:
         Maximum number of pages to show in the pagination.

   :Default:
         10

 - :Property:
         settings.event.errorHandling

   :Date type:
         String

   :Description:
         If an event for the detail and registration view is not found (e.g. is hidden or deleted), you can configure,
         if the plugin should redirect to the list view, show a 404 error or render the view (default) without the
         event data.

         Possible values:

         * redirectToListView
         * pageNotFoundHandler
         * showStandaloneTemplate

         The "showStandaloneTemplate" option requires a Template and optional an HTTP status code.

         Example: showStandaloneTemplate,EXT:sf_event_mgt/Resources/Private/Templates/Event/EventNotFound.html,404

         **Note:** For TYPO3 9.5, this setting has only effect when the event is not passed through GET parameters to the
         action (e.g. event set in plugin). For all other scenarios, the TYPO3 "sites" error handling steps in.

   :Default:
         showStandaloneTemplate,EXT:sf_event_mgt/Resources/Private/Templates/Event/EventNotFound.html,404

module.tx_sfeventmgt
~~~~~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Date type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:

 - :Property:
         settings.csvExport.fields

   :Date type:
         String

   :Description:
         Comma seperated list of fields to include in CSV export. Please note, that you must write the **property
         names** of the fields to export (e.g. firstname, lastname, dateOfBirth, event.title)

         In order to export the values of registration fields, use "registration_fields" as fieldname. Note, that
         it is only possible to export all registrations fields at once.

   :Default:
         uid, gender, firstname, lastname, title, company, email, address, zip, city, country, registration_fields

 - :Property:
         settings.csvExport.showFlashMessageForInsufficientAccessRights

   :Date type:
         Boolean

   :Description:
         If switched on, a warning message is shown in the backend module, when a backend user does not have
         read/write access rights to the temp-folder of the default storage.

   :Default:
         true

 - :Property:
         settings.csvExport.fieldDelimiter

   :Date type:
         String

   :Description:
         Comma seperated list delimiter

   :Default:
         ,

 - :Property:
         settings.csvExport.fieldQuoteCharacter

   :Date type:
         String

   :Description:
         Comma seperated list quote character

   :Default:
         "

 - :Property:
         settings.csvExport.prependBOM

   :Date type:
         Boolean

   :Description:
         Prepend UTF-8 BOM to export. Switch this setting on, of you have problems when opening the exported CSV file
         with Microsoft Excel

   :Default:
         false

 - :Property:
         settings.list.itemsPerPage

   :Date type:
         Integer

   :Description:
         Number of items to show per page in backend module

   :Default:
         10

 - :Property:
         settings.search.dateFormat

   :Date type:
         String

   :Description:
         Date format for search fields in backend module

   :Default:
         d.m.Y H:i

 - :Property:
         settings.search.fields

   :Date type:
         String

   :Description:
         Fields to be included in a query from the backend module

   :Default:
         title, teaser

 - :Property:
         settings.notification.senderEmail

   :Date type:
         String

   :Description:
         E-mail address for emails sent to user

   :Default:
         Empty

 - :Property:
         settings.notification.senderName

   :Date type:
         String

   :Description:
         Sender name for emails sent to user

   :Default:
         Empty

 - :Property:
         settings.notification.senderSignature

   :Date type:
         String

   :Description:
         Signature shown in emails sent to user

   :Default:
         Empty

 - :Property:
         settings.notification.customNotifications.{templatename}

   :Date type:
         String

   :Description:
         Name of custom notification template. Custom notifications can be
         sent to all registered participants of an event in the administration
         module.

         **Example for default custom notification**

         .. figure:: ../../../Images/event-notification.png
             :alt: Custom notifications

         Each custom notification must include a **title**, a **template** and a **subject**

         Please refer to the default custom notitication for setup example.

   :Default:
         thanksForParticipation

 - :Property:
         settings.enabledActions.notify

   :Date type:
         Boolean

   :Description:
         If set to "1", the Notify-Action / Icon is shown for events with registration enabled.

   :Default:
         1 (true)

 - :Property:
         settings.enabledActions.export

   :Date type:
         Boolean

   :Description:
         If set to "1", the Export-Action / Icon is shown for events with registration enabled.

   :Default:
         1 (true)
