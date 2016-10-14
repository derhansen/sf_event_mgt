.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _tsref:

TypoScript reference
====================

More TypoScript settings are available on the :ref:`eventplugin-settings` page.

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
         settings.clearCacheUids

   :Date type:
         String

   :Description:
         List of pids, for which the cache should be cleared after registration data changed

   :Default:
         Empty

 - :Property:
         settings.eventDetails.imageWidth

   :Date type:
         Integer

   :Description:
         Default width of images in detail view

   :Default:
         200

 - :Property:
         settings.eventDetails.imageHeight

   :Date type:
         Integer

   :Description:
         Default height of images in detail view

   :Default:
         Empty

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
         If set to true, expired registrations will be deleted. If this setting is set to false,
         expired registrations will just be set to **hidden**

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
         * recaptcha

         Note, that all fields are just checked, if they are empty or not. If the field "accepttc" (or any other
         boolean field) is included in the list of required fields, it is checked if the field value is true.

   :Default:
         empty

 - :Property:
         settings.reCaptcha.siteKey

   :Date type:
         string

   :Description:
         Sitekey for reCATCHA check. Note: If this setting is left blank, the reCAPTCHA field will not be shown

   :Default:
         empty

 - :Property:
         settings.reCaptcha.secretKey

   :Date type:
         string

   :Description:
         SecretKey for reCATCHA check.

   :Default:
         empty

 - :Property:
         settings.confirmation.prefillFields.{fieldname}

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
         E-mail address for e-mails sent to user

   :Default:
         empty

 - :Property:
         settings.notification.senderName

   :Date type:
         String

   :Description:
         Sender name for e-mails sent to user

   :Default:
         empty

 - :Property:
         settings.notification.senderSignature

   :Date type:
         String

   :Description:
         Signature shown in e-mails sent to user

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
         settings.notification.registrationNew.userSubject

   :Date type:
         String

   :Description:
         User-Subject for new registration

   :Default:
         Your event registration

 - :Property:
         settings.notification.registrationWaitlistNew.userSubject

   :Date type:
         String

   :Description:
         User-Subject for new registration on the waitlist

   :Default:
         Your event registration on the waitlist

 - :Property:
         settings.notification.registrationNew.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for new registration

   :Default:
         New unconfirmed event registration

 - :Property:
         settings.notification.registrationWaitlistNew.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for new registration on the waitlist

   :Default:
         New unconfirmed event registration on the waitlist

 - :Property:
         settings.notification.registrationConfirmed.userSubject

   :Date type:
         String

   :Description:
         User-Subject for confirmed registration

   :Default:
         Event registration successful

 - :Property:
         settings.notification.registrationWaitlistConfirmed.userSubject

   :Date type:
         String

   :Description:
         User-Subject for confirmed registration on the waitlist

   :Default:
         Event registration on the waitlist successful

 - :Property:
         settings.notification.registrationConfirmed.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for confirmed registration

   :Default:
         Event registration confirmed

 - :Property:
         settings.notification.registrationWaitlistConfirmed.adminSubject

   :Date type:
         String

   :Description:
         Admin-Subject for confirmed registration on the waitlist

   :Default:
         Event registration on the waitlist confirmed

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
         names** of the fields to export (e.g. firstname, lastname, dateOfBirth)

   :Default:
         uid, gender, firstname, lastname, title, company, email, address, zip, city, country

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
         E-mail address for e-mails sent to user

   :Default:
         Empty

 - :Property:
         settings.notification.senderName

   :Date type:
         String

   :Description:
         Sender name for e-mails sent to user

   :Default:
         Empty

 - :Property:
         settings.notification.senderSignature

   :Date type:
         String

   :Description:
         Signature shown in e-mails sent to user

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

         .. figure:: ../../../Images/event-custom-notification.png
             :align: left
             :alt: Custom notifications

         Each custom notification must include a **title**, a **template** and a **subject**

         Please refer to the default custom notitication for setup example.

   :Default:
         thanksForParticipation

