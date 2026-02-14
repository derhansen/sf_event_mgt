.. include:: /Includes.rst.txt

.. highlight:: typoscript
.. _tsref:

====================
TypoScript reference
====================

More TypoScript settings are available on the :ref:`eventplugin-settings` page. Also make sure to check the included
:php:`setup.typoscript` file for available/default TypoScript settings.

plugin.tx_sfeventmgt
====================

.. t3-field-list-table::
 :header-rows: 1

view.templateRootPath
---------------------

.. confval:: view.templateRootPath

   :Type: string
   :Default: **Extbase default**

   Path to the templates. The default setting is EXT:sf_event_mgt/Resources/Private/Templates/


view.partialRootPath
--------------------

.. confval:: view.partialRootPath

   :Type: string
   :Default: **Extbase default**

   Path to the partials. The default setting is EXT:sf_event_mgt/Resources/Private/Partials/


view.layoutRootPath
-------------------

.. confval:: view.layoutRootPath

   :Type: string
   :Default: **Extbase default**

   Path to the layouts. The default setting is EXT:sf_event_mgt/Resources/Private/Layouts/


settings.calendar.firstDayOfWeek
--------------------------------

.. confval:: settings.calendar.firstDayOfWeek

   :Type: int
   :Default: 1

   First day of week 0 (Sunday) bis 6 (Saturday). The default value "1" is set to Monday.


settings.calendar.includeEventsForEveryDayOfAllCalendarWeeks
------------------------------------------------------------

.. confval:: settings.calendar.includeEventsForEveryDayOfAllCalendarWeeks

   :Type: int
   :Default: 1

   If set, the calendar will show events for all days of all shown weeks of the calendar and not only
   events for the current month.


settings.calendar.showWeekNumber
--------------------------------

.. confval:: settings.calendar.showWeekNumber

   :Type: int
   :Default: 1

   Defines if the calendar should show week numbers or not.


settings.detail.checkPidOfEventRecord
-------------------------------------

.. confval:: settings.detail.checkPidOfEventRecord

   :Type: int
   :Default: 0

   If set, the detail view checks the incoming event record against the defined starting point(s).
   If those don’t match, the event record won’t be displayed.


settings.detail.imageWidth
--------------------------

.. confval:: settings.detail.imageWidth

   :Type: int
   :Default: 200

   Default width of images in detail view


settings.detail.imageHeight
---------------------------

.. confval:: settings.detail.imageHeight

   :Type: int
   :Default: (none)

   Default height of images in detail view


settings.detail.isShortcut
--------------------------

.. confval:: settings.detail.isShortcut

   :Type: int
   :Default: 0

   This setting should be set to "1" if the event should be fetched from the Content Object data.
   This option should only be set to "1", if events are displayed using the "Insert Record" content element


settings.registration.checkPidOfEventRecord
-------------------------------------------

.. confval:: settings.registration.checkPidOfEventRecord

   :Type: int
   :Default: 0

   If set, the registration view checks the incoming event record against the defined starting point(s).
   If those don’t match, the registration to the event is not possible.


settings.registration.autoConfirmation
--------------------------------------

.. confval:: settings.registration.autoConfirmation

   :Type: int
   :Default: 0

   If set to `1`, new registration will automatically be confirmed by redirecting
   the user to the confirmRegistration-Action.


settings.registration.deleteExpiredRegistrations
------------------------------------------------

.. confval:: settings.registration.deleteExpiredRegistrations

   :Type: int
   :Default: 0

   If set to `1`, expired registrations will be deleted by the action in the backend module. If this
   setting is set to false, expired registrations will just be set to **hidden**

   Note, this setting has no effect for the `cleanup` CLI command.


settings.registration.formatDateOfBirth
---------------------------------------

.. confval:: settings.registration.formatDateOfBirth

   :Type: string
   :Default: d.m.Y

   Date format of field dateOfBirth


settings.registration.requiredFields
------------------------------------

.. confval:: settings.registration.requiredFields

   :Type: string
   :Default: empty

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


settings.registration.linkTermsAndConditions
--------------------------------------------

.. confval:: settings.registration.linkTermsAndConditions

   :Type: string
   :Default: empty

   A page or an external URL that can be used in the registration template to show "Terms & Conditions"


settings.registration.prefillFields.{fieldname}
-----------------------------------------------

.. confval:: settings.registration.prefillFields.{fieldname}

   :Type: string

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


settings.registration.rateLimit.enabled
---------------------------------------

.. confval:: settings.registration.rateLimit.enabled

   :Type: int
   :Default: 0

   If set to `1`, the registration form will have a reate limit enabled.


settings.registration.rateLimit.limit
-------------------------------------

.. confval:: settings.registration.rateLimit.limit

   :Type: int
   :Default: 5

   Amount of successful registrations allowed in the configured timeframe, before new successful registration
   attempts are rate limited.


settings.registration.rateLimit.interval
----------------------------------------

.. confval:: settings.registration.rateLimit.interval

   :Type: string
   :Default: 15 minutes

   Defines the interval for which the configured `limit` applies.


settings.registration.rateLimit.handling
----------------------------------------

.. confval:: settings.registration.rateLimit.handling

   :Type: string
   :Default: flashMessage

   Defines how to handle a possible rate limit. Valid values are:

   * `flashMessage`: Add a flash message to the message queue and forwards back to previous action.
   * `httpResponse429`: Returns a 429 HTTP response with a message.

The message is defined in `rateLimiter.exceeded` localization key.

Note: If you use the setting `flashMessage`, you must output `<f:flashMessages />` in your registration template.


settings.waitlist.moveUp.keepMainRegistrationDependency
-------------------------------------------------------

.. confval:: settings.waitlist.moveUp.keepMainRegistrationDependency

   :Type: int
   :Default: 0

   If set to `1`, a registration will keep the dependency to the main registration if the registration
   has been submitted using the simultaneous registration process. Note, that it is recommended to set this
   value to false (0), since cancellation of the main registration will also cancel moved up "child"
   registrations.


settings.confirmation.linkValidity
----------------------------------

.. confval:: settings.confirmation.linkValidity

   :Type: int
   :Default: 3600

   Validity of confirmation link in seconds


settings.confirmation.additionalVerificationStep
------------------------------------------------

.. confval:: settings.confirmation.additionalVerificationStep

   :Type: bool
   :Default: false

   Defines, if the confirmation of a registration requires an additional manual verification step by the user.
   If active, confirmation links in emails will refer to a page, where the user has to confirm the registration
   by clicking a link.

   .. note::

      Please ensure, that the :php:`action` argument for the :php:`f:link.action` ViewHelper in your notification
      templates equal to :php:`{cancelAction}`

settings.cancellation.additionalVerificationStep
------------------------------------------------

.. confval:: settings.cancellation.additionalVerificationStep

   :Type: bool
   :Default: false

   Defines, if the cancellation of a registration requires an additional manual verification step by the user.
   If active, cancellation links in emails will refer to a page, where the user has to confirm the cancellation
   by clicking a link.

   .. note::

      Please ensure, that the :php:`action` argument for the :php:`f:link.action` ViewHelper in your notification
      templates equal to :php:`{cancelAction}`


settings.notification.senderEmail
---------------------------------

.. confval:: settings.notification.senderEmail
   :name: ts-settings-senderemail
   :Type: string
   :Default: empty

   E-mail address for emails sent to user


settings.notification.senderName
--------------------------------

.. confval:: settings.notification.senderName
   :name: ts-settings-sendername
   :Type: string
   :Default: empty

   Sender name for emails sent to user


settings.notification.replyToEmail
----------------------------------

.. confval:: settings.notification.replyToEmail

   :Type: string
   :Default: empty

   Reply-to email address of emails sent to the user


settings.notification.senderSignature
-------------------------------------

.. confval:: settings.notification.senderSignature
   :name: ts-settings-sendersignature
   :Type: string
   :Default: empty

   Signature shown in emails sent to user


settings.notification.adminEmail
--------------------------------

.. confval:: settings.notification.adminEmail

   :Type: string
   :Default: empty

   Admin E-mail address


settings.notification.registrationDataAsSenderForAdminEmails
------------------------------------------------------------

.. confval:: settings.notification.registrationDataAsSenderForAdminEmails

   :Type: int
   :Default: false

   If set, admin emails will be sent by the email-address and sender name (firstname and lastname)
   set in the registration


settings.notification.disabled
------------------------------

.. confval:: settings.notification.disabled

   :Type: int
   :Default: false

   If set, the email notification feature is completely disabled. This includes user and admin emails as well
   as user notifications from the backend.

   Note, that the functionality of sending Notifications in the backend module is also disabled when this option
   is set to "true"


settings.notification.registrationNew.userSubject
-------------------------------------------------

.. confval:: settings.notification.registrationNew.userSubject

   :Type: string
   :Default: Your event registration

   User-Subject for new registration


settings.notification.registrationNew.adminSubject
--------------------------------------------------

.. confval:: settings.notification.registrationNew.adminSubject

   :Type: string
   :Default: New unconfirmed event registration

   Admin-Subject for new registration


settings.notification.registrationNew.attachments
-------------------------------------------------

.. confval:: settings.notification.registrationNew.attachments

   :Type: string
   :Default: empty

   Attachment configuration for new unconfirmed event registrations. See :ref:`email-attachments`


settings.notification.registrationWaitlistNew.userSubject
---------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistNew.userSubject

   :Type: string
   :Default: Your event registration on the waitlist

   User-Subject for new registration on the waitlist


settings.notification.registrationWaitlistNew.adminSubject
----------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistNew.adminSubject

   :Type: string
   :Default: New unconfirmed event registration on the waitlist

   Admin-Subject for new registration on the waitlist


settings.notification.registrationWaitlistNew.attachments
---------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistNew.attachments

   :Type: string
   :Default: empty

   Attachment configuration for new unconfirmed event registrations on the waitlist. See :ref:`email-attachments`


settings.notification.registrationConfirmed.userSubject
-------------------------------------------------------

.. confval:: settings.notification.registrationConfirmed.userSubject

   :Type: string
   :Default: Event registration successful

   User-Subject for confirmed registration


settings.notification.registrationConfirmed.adminSubject
--------------------------------------------------------

.. confval:: settings.notification.registrationConfirmed.adminSubject

   :Type: string
   :Default: Event registration confirmed

   Admin-Subject for confirmed registration


settings.notification.registrationConfirmed.attachments
-------------------------------------------------------

.. confval:: settings.notification.registrationConfirmed.attachments

   :Type: string
   :Default: empty

   Attachment configuration for confirmed event registrations. See :ref:`email-attachments`


settings.notification.registrationWaitlistConfirmed.userSubject
---------------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistConfirmed.userSubject

   :Type: string
   :Default: Event registration on the waitlist successful

   User-Subject for confirmed registration on the waitlist


settings.notification.registrationWaitlistConfirmed.adminSubject
----------------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistConfirmed.adminSubject

   :Type: string
   :Default: Event registration on the waitlist confirmed

   Admin-Subject for confirmed registration on the waitlist


settings.notification.registrationWaitlistConfirmed.attachments
---------------------------------------------------------------

.. confval:: settings.notification.registrationWaitlistConfirmed.attachments

   :Type: string
   :Default: empty

   Attachment configuration for confirmed event registrations on the waitlist. See :ref:`email-attachments`


settings.notification.registrationCancelled.userSubject
-------------------------------------------------------

.. confval:: settings.notification.registrationCancelled.userSubject

   :Type: string
   :Default: Event registration cancelled successful

   User-Subject for cancelled registration


settings.notification.registrationCancelled.adminSubject
--------------------------------------------------------

.. confval:: settings.notification.registrationCancelled.adminSubject

   :Type: string
   :Default: Event registration cancelled

   Admin-Subject for cancelled registration


settings.search.dateFormat
--------------------------

.. confval:: settings.search.dateFormat
   :name: ts-search-date-format
   :Type: string
   :Default: Y-m-d

   Date format for date fields in the search view


settings.search.fields
----------------------

.. confval:: settings.search.fields
   :name: ts-settings-search-fields
   :Type: string
   :Default: title, teaser

   Fields to be included in a query for the search view


settings.search.adjustTime
--------------------------

.. confval:: settings.search.adjustTime

   :Type: int
   :Default: true

   When the setting `settings.search.dateFormat` is set to a date only, it is recommended to set this option
   to true. The time for a given startdate will be set to 00:00:00 and the time for a given enddate will be set
   to 23:59:59, so all events for the given dates will be found by a search.


settings.pagination.enablePagination
------------------------------------

.. confval:: settings.pagination.enablePagination
   :name: ts-settings-pagination-enablepagination
   :Type: int
   :Default: false

   If true, the list view outputs required variables to render a pagination.


settings.pagination.itemsPerPage
--------------------------------

.. confval:: settings.pagination.itemsPerPage
   :name: ts-settings-pagination-itemsperpage
   :Type: int
   :Default: 10

   Amount of items per paginated page.


settings.pagination.maxNumPages
-------------------------------

.. confval:: settings.pagination.maxNumPages
   :name: ts-settings-pagination-maxnumpages
   :Type: int
   :Default: 10

   Maximum number of pages to show in the pagination.


settings.event.errorHandling
----------------------------

.. confval:: settings.event.errorHandling

   :Type: string
   :Default: showStandaloneTemplate,EXT:sf_event_mgt/Resources/Private/Templates/Event/EventNotFound.html,404

   If an event for the detail and registration view is not found (e.g. is hidden or deleted), you can configure,
   if the plugin should redirect to the list view, show a 404 error or render the view (default) without the
   event data.

   Possible values:

   - redirectToListView
   - pageNotFoundHandler
   - showStandaloneTemplate

   The "showStandaloneTemplate" option requires a Template and optional an HTTP status code.

   Example: showStandaloneTemplate,EXT:sf_event_mgt/Resources/Private/Templates/Event/EventNotFound.html,404

   **Note:** For TYPO3 9.5, this setting has only effect when the event is not passed through GET parameters to the
   action (e.g. event set in plugin). For all other scenarios, the TYPO3 "sites" error handling steps in.


module.tx_sfeventmgt
====================

settings.csvExport.fields
-------------------------

.. confval:: settings.csvExport.fields

   :Type: string
   :Default: uid, gender, firstname, lastname, title, company, email, address, zip, city, country, registration_fields

   Comma-separated list of fields to include in CSV export. Please note, that you must write the **property
   names** of the fields to export (e.g. firstname, lastname, dateOfBirth, event.title)

   In order to export the values of registration fields, use "registration_fields" as fieldname. Note, that
   it is only possible to export all registration fields at once.


settings.csvExport.showFlashMessageForInsufficientAccessRights
--------------------------------------------------------------

.. confval:: settings.csvExport.showFlashMessageForInsufficientAccessRights

   :Type: int
   :Default: true

   If switched on, a warning message is shown in the backend module, when a backend user does not have
   read/write access rights to the temp-folder of the default storage.


settings.csvExport.fieldDelimiter
---------------------------------

.. confval:: settings.csvExport.fieldDelimiter

   :Type: string
   :Default: ,

   Comma-separated list delimiter


settings.csvExport.fieldQuoteCharacter
--------------------------------------

.. confval:: settings.csvExport.fieldQuoteCharacter

   :Type: string
   :Default: "

   Comma-separated list quote character


settings.csvExport.prependBOM
-----------------------------

.. confval:: settings.csvExport.prependBOM

   :Type: int
   :Default: 0

   Prepend UTF-8 BOM to export. Switch this setting on if you have problems when opening the exported CSV file
   with Microsoft Excel


settings.list.itemsPerPage
--------------------------

.. confval:: settings.list.itemsPerPage

   :Type: int
   :Default: 10

   Number of items to show per page in the backend module


settings.search.dateFormat
--------------------------

.. confval:: settings.search.dateFormat
   :name: ts-search-date-format-backend-module
   :Type: string
   :Default: d.m.Y H:i

   Date format for search fields in the backend module


settings.search.fields
----------------------

.. confval:: settings.search.fields
   :name: ts-settings-search-fields-backend-module
   :Type: string
   :Default: title, teaser

   Fields to be included in a query from the backend module


settings.notification.senderEmail
---------------------------------

.. confval:: settings.notification.senderEmail
   :name: ts-settings-senderemail-backend-module
   :Type: string
   :Default: (none)

   E-mail address for emails sent to user


settings.notification.senderName
--------------------------------

.. confval:: settings.notification.senderName
   :name: ts-settings-sendername-backend-module
   :Type: string
   :Default: (none)

   Sender name for emails sent to user


settings.notification.senderSignature
-------------------------------------

.. confval:: settings.notification.senderSignature
   :name: ts-settings-sendersignature-backend-module
   :Type: string
   :Default: (none)

   Signature shown in emails sent to user


settings.notification.customNotifications.{templatename}
--------------------------------------------------------

.. confval:: settings.notification.customNotifications.{templatename}

   :Type: string

   Name of custom notification template. Custom notifications can be
   sent to all registered participants of an event in the administration
   module.

   **Example for default custom notification**

   .. figure:: /Images/event-notification.png
       :alt: Custom notifications
       :class: with-shadow

   Each custom notification must include a **title**, a **template**, and a **subject**

   Please refer to the default custom notification for a setup example.

   :Default: thanksForParticipation


settings.enabledActions.notify
------------------------------

.. confval:: settings.enabledActions.notify

   :Type: int
   :Default: 1

   If set to "1", the Notify-Action / Icon is shown for events with registration enabled.


settings.enabledActions.export
------------------------------

.. confval:: settings.enabledActions.export

   :Type: int
   :Default: 1

   If set to "1", the Export-Action / Icon is shown for events with registration enabled.

settings.disableButtons.newEvent
--------------------------------

.. confval:: settings.disableButtons.newEvent

   :Type: int
   :Default: 0

   If set to "1", the "Create new event" button is disabled in the administration module.

settings.disableButtons.newLocation
-----------------------------------

.. confval:: settings.disableButtons.newLocation

   :Type: int
   :Default: 0

   If set to "1", the "Create new location" button is disabled in the administration module.

settings.disableButtons.newOrganisator
--------------------------------------

.. confval:: settings.disableButtons.newOrganisator

   :Type: int
   :Default: 0

   If set to "1", the "Create new organisator" button is disabled in the administration module.

settings.disableButtons.newSpeaker
----------------------------------

.. confval:: settings.disableButtons.newSpeaker

   :Type: int
   :Default: 0

   If set to "1", the "Create new speaker" button is disabled in the administration module.

settings.disableButtons.handleExpiredRegistrations
--------------------------------------------------

.. confval:: settings.disableButtons.handleExpiredRegistrations

   :Type: int
   :Default: 0

   If set to "1", the "Hide/delete expired registrations" button is disabled in the administration module.

settings.defaultSorting.orderField
------------------------------

.. confval:: settings.defaultSorting.orderField

   :Type: string
   :Default: title

   Defines the default field to be used for sorting. When not explicitly
   specified, the sorting will be based on the "title" field.

settings.defaultSorting.orderDirection
----------------------------------------

.. confval:: settings.defaultSorting.orderDirection

   :Type: string
   :Default: asc

   Specifies the default order direction. The default value "asc" stands for
   ascending order. Can be set to "desc" for descending order.

settings.pagination.enablePagination
------------------------------------

.. confval:: settings.pagination.enablePagination
   :name: ts-settings-pagination-enablepagination-backend-module
   :Type: int
   :Default: 1

   Determines whether pagination is enabled (1) or disabled (0). When set to
   "1", pagination is used to divide content into separate pages.

settings.pagination.itemsPerPage
--------------------------------

.. confval:: settings.pagination.itemsPerPage
   :name: ts-settings-pagination-itemsperpage-backend-module
   :Type: int
   :Default: 10

   Specifies the number of items to display on each page when pagination is
   enabled. The default setting is 10 items per page.

settings.pagination.maxNumPages
-------------------------------

.. confval:: settings.pagination.maxNumPages
   :name: ts-settings-pagination-maxnumpages-backend-module
   :Type: int
   :Default: 10

   Sets the maximum number of pages to display in the pagination control.
   The default is set to 10 pages.
