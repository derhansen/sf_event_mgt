.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _email-attachments:

E-Mail attachments
==================

If the registration option is enabled for an event, participants can register to the
event. The extension allows you to send e-mails to the participant in order to notify
him, that he has registered or confirmed his registration.

The extension supports to add attachments to the following type of e-mails:

* New event registration
* New event registration on the waitlist
* Confirmed event registration
* Confirmed event registration on the waitlist

Configuring e-mail attachments
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Attachments must be configured in TypoScript and it is possible to add attachments globally to all e-mails of a
specific type or individual per event. Due to readability, the default TypoScript setup does not include an
example configuration for e-mail attachments.

Possible attachment configurations
----------------------------------

Attachment configuration can be added to the following TypoScript settings:

* ``plugin.tx_sfeventmgt.settings.notification.registrationNew``
* ``plugin.tx_sfeventmgt.settings.notification.registrationWaitlistNew``
* ``plugin.tx_sfeventmgt.settings.notification.registrationConfirmed``
* ``plugin.tx_sfeventmgt.settings.notification.registrationWaitlistConfirmed``

Note, that you also need to configure the recipient group (``user`` or ``admin``).

Properties for attachment configuration
---------------------------------------

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
         fromFiles

   :Date type:
         array

   :Description:
         Array of files located on the web storage.

   :Default:
         empty

 - :Property:
         fromEventProperty

   :Date type:
         array

   :Description:
         Array of property names of the event, which contains one or multiple files to be attached to the e-mail

   :Default:
         empty

 - :Property:
         fromRegistrationProperty

   :Date type:
         array

   :Description:
         Array of property names of the registration, which contains one or multiple files to be attached to the e-mail

   :Default:
         empty

Example for new event registrations:
------------------------------------

::

  plugin.tx_sfeventmgt {
    settings {
      notification {
        registrationNew {
          attachments {
            user {
              fromFiles {
                1 = fileadmin/terms-and-conditions.pdf
              }
              fromEventProperty {
                1 = files
                2 = image
              }
              fromRegistrationProperty {
                1 = registrationFiles
              }
            }
            admin {
              fromFiles =
              fromEventProperty =
              fromRegistrationProperty =
            }
          }
        }
      }
    }
  }

The example above configures the attachments for e-mails to the user (the participant) when a new registration is
created.

The ``fromFiles`` setting configures the file ``fileadmin/terms-and-conditions.pdf`` to be added to the e-mail. If the
file does not exist, it will not be added.

The ``fromEventProperty`` setting configures to add all files from the event properties ``files`` and ``image``. Those
properties are of the type ``\TYPO3\CMS\Extbase\Persistence\ObjectStorage`` and may contain fileReferences. It is also
possible to use properties of the type ``\TYPO3\CMS\Extbase\Domain\Model\FileReference``

The configuration ot the ``fromRegistrationProperty`` setting is similar to the ``fromEventProperty`` setting. In the
example above, the property ``registrationFiles`` will be used. Note, that the registration model of the extension does
not contain any default fields that can be used as attachments, so you have to add your own if you need them.

iCal attachment
===============

Configuration of an iCal attachment is similar to the configuration of attachments (see above). The only difference is,
that the iCal attachment is only supported for the recipient group ``user``

Properties for attachment configuration
---------------------------------------

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
         iCalFile

   :Date type:
         boolean

   :Description:
         If set, a iCal file for the event will be attached to the user e-mail

   :Default:
         empty

Example for new event registrations:
------------------------------------

::

  plugin.tx_sfeventmgt {
    settings {
      notification {
        registrationNew {
          attachments {
            user {
              iCalFile = 1
            }
          }
        }
      }
    }
  }

In the example above, e-mails for new user registrations will include an iCal file for the event.