.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _registrations:

=============
Registrations
=============

If the registration option is enabled for an event, participants can register to the
event. A registration contains the data the participant entered during the registration
process and also some administration fields like "confirmed" or "paid".

Default fields in the registration form are:

* Gender
* Title
* Firstname
* Lastname
* Company
* Address
* Zip
* City
* Country
* Phone
* E-Mail
* Date of birth
* Notes
* Accept terms and conditions

Additionally, the following fields are only shown under certain conditions:

* Amount of registrations (if Max. simultaneous registrations per user > 1)
* Price option (only if event has price options)
* Payment method (only if event has enabled payment)

If you need additional field in the registration form, you can add individual
fields on event basis. For more information, see :ref:`registrationfield`


.. figure:: /Images/registration.png
   :alt: Registration record
   :class: with-shadow

General
~~~~~~~
The general tab contains personal data about the participant, who registered to the event

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Gender

   :Description:
         Gender of the participant

 - :Field:
         Title

   :Description:
         Title of the participant

 - :Field:
         Firstname

   :Description:
         Firstname of participant

 - :Field:
         Lastname

   :Description:
         Lastname of participant

 - :Field:
         Company

   :Description:
         Company of participant

 - :Field:
         Address

   :Description:
         Address of participant

 - :Field:
         Zip

   :Description:
         Zip of the participant

 - :Field:
         City

   :Description:
         City of the participant

 - :Field:
         Country

   :Description:
         Country of the participant

 - :Field:
         Phone

   :Description:
         Phone of the participant

 - :Field:
         E-mail

   :Description:
         E-mail of the participant

 - :Field:
         Date of birth

   :Description:
         Date of birth of the participant

 - :Field:
         Accepted terms and conditions

   :Description:
         Indicates, that the user has confirmed the terms and conditions (if field is used in template)

 - :Field:
         Notes

   :Description:
         Notes from the participant

 - :Field:
         Registration date

   :Description:
         The date the registration was created. This field can be edited in the backend, so you can control which
         registration will move up from the waitlist for the default waitlist move up process.


Additional
~~~~~~~~~~
The additional tab contains additional data about the registration

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Frontend user

   :Description:
         If there was a valid frontend user session at registration time, a relation to the frontend user record
         is saved in this field

 - :Field:
         Confirmation until

   :Description:
         Administration field. Date/time until the registration must be confirmed. Hides automatically,
         when the registration has been confirmed.

 - :Field:
         Confirmed

   :Description:
         Administration field. Will be set automatically, when the user confirms the registration.

 - :Field:
         No email notifications

   :Description:
         It this field is set to true, the participant will not receive notifications sent by the
         backend module.

 - :Field:
         Amount of registrations

   :Description:
         Read-only field which shows the number of registrations the participant has created. Only
         shown, if participant has created more than one registration

 - :Field:
         Parent registration

   :Description:
         Read-only field which shows the parent registration. Only shown, if the registration depends
         on another registration (multiple registrations created by the same participant)

 - :Field:
         Registration waitlist

   :Description:
         If checked, the registration is on the waitlist


Registration field values
~~~~~~~~~~~~~~~~~~~~~~~~~
The registration fields tab contains all submitted registration field values.

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Registration field values

   :Description:
         List of registration field values


Payment
~~~~~~~
The payment tab contains information about payment of the registration

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Paid

   :Description:
         Administration field used to set if the user has paid for the event

 - :Field:
         Price (when registered)

   :Description:
         The price of the registration when the user registered to the event.
         This is either the field "price" from the event record or the
         price of the selected price option, if the event has price options.

 - :Field:
         Price Option

   :Description:
         The price option the user selected on registration

 - :Field:
         Payment method

   :Description:
         Selected payment method on registration

 - :Field:
         Payment reference

   :Description:
         This field can be used by a payment extension for sf_event_mgt to store a payment reference

