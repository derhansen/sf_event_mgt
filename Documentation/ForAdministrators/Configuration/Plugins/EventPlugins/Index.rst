.. include:: /Includes.rst.txt

.. highlight:: typoscript
.. _eventplugin-settings:

=====================================================================
List view, Detail view, Registration view, Calendar view, Search view
=====================================================================

Since some plugins use the same settings, this section covers the settings
for the following plugins:

* List view
* Detail view
* Registration view
* Calendar view
* Search view

Nearly all important settings can be made through the plugins, which override the
settings made with TypoScript.

.. only:: html

   .. contents:: Properties
      :depth: 1
      :local:

Tab settings
~~~~~~~~~~~~

Display Mode
------------

.. confval:: displayMode

   :Type: string
   :Default: all
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   With this setting, the plugin can be configured to show **all** events, only **future** or only **past events**.

   Available options:

   - all
   - future
   - current_future
   - past
   - time_restriction

   .. note::

      Display mode `past` will not include events that have no enddate.

Show a Single Event Record
--------------------------

.. confval:: singleEvent

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Detail, Registration

   The detail view will show the configured event record if no event is passed to the detail or registration
   action by parameter. Can be used to display a single event on a page without the need to link to the detail
   or registration page from a list view.

Sort By
-------

.. confval:: orderField

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Defines which field should be used for sorting events in the frontend.

Sorting Direction
-----------------

.. confval:: orderDirection

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Defines the sorting direction for `orderField`.

   Possible values:

   - (empty value)
   - asc
   - desc

Top Event Restriction
---------------------

.. confval:: topEventRestriction

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   With this setting, the plugin can be configured to show **only top event** events, to **except top events**,
   or to ignore the top event restriction.

   Available options:

   - 0 (None - ignore top event restriction)
   - 1 (Except top events)
   - 2 (Only top events)

Max Records Displayed
---------------------

.. confval:: queryLimit

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   The maximum number of records shown.

Category Mode
-------------

.. confval:: categoryConjunction

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   This setting defines how categories are taken into account when selecting events.

   The following options are available:

   - Ignore category selection
   - Show events with selected categories (`OR`)
   - Show events with selected categories (`AND`)
   - Do NOT show events with selected categories (`NOTOR`)
   - Do NOT show events with selected categories (`NOTAND`)

Category
--------

.. confval:: category

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Restrict events to be shown by one or more categories.

Include Subcategory
-------------------

.. confval:: includeSubcategories

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Includes subcategories of the selected category.

Location
--------

.. confval:: location

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Restrict events to be shown by one location.

Organisator
-----------

.. confval:: organisator

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Restrict events to be shown by one organiser.

Speaker
-------

.. confval:: speaker

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Restrict events to be shown by one speaker.

Record Storage Page
-------------------

.. confval:: storagePage

   :Type: int or list of ints
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   One or more sysfolders where events are stored.

Recursive
---------

.. confval:: recursive

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   Recursion level for record storage page.

Comma Separated List of Field Names, Which Are Required
-------------------------------------------------------

.. confval:: registration.requiredFields

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   List of field names that are mandatory for registration. The fields
   firstname, lastname, and email are always required and cannot be overridden.

   The following additional fields are available:

   - title
   - company
   - address
   - zip
   - city
   - country
   - phone
   - gender
   - dateOfBirth
   - notes
   - accepttc

   Note that all fields are checked if they are empty or not. If the field "accepttc" (or any other
   boolean field) is included in the list of required fields, it is checked if the field value is true.


Tab additional
~~~~~~~~~~~~~~

Detail Page
-----------

.. confval:: detailPid

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   Page where the plugin is configured to show event details.

List Page
---------

.. confval:: listPid

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   Page where the list view for events is shown. Only available when the plugin is configured to show event details.

Registration Page
-----------------

.. confval:: registrationPid

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   Page where the plugin is configured to show event registration.

Payment Page
------------

.. confval:: paymentPid

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   Page where the plugin is configured to handle payments for registration.

Restrict Foreign Records to Storage Page
----------------------------------------

.. confval:: restrictForeignRecordsToStoragePage

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Categories, locations, and organizers will only be loaded from the configured storage page (recursive).

Disable Override Demand
-----------------------

.. confval:: disableOverrideDemand

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   If set, the settings of the plugin can't be overridden by arguments in the URL.

Tab template
~~~~~~~~~~~~

Template Layout
---------------

.. confval:: templateLayout

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar, Registration, Detail

   With this setting, the plugin can be configured to show different template layouts.

   * Template layouts can be configured with Page TSConfig.
   * Template layout can be used/set by TypoScript (`settings.templateLayout`)


Tab notification
~~~~~~~~~~~~~~~~

Notification Configuration
---------------------------

.. confval:: notification.senderEmail

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Email address of emails sent to the user. This should be the email address of the site admin or a general information
   email address. The user will see this email address as sender.


.. confval:: notification.senderName

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Name of the sender.


.. confval:: notification.replyToEmail

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Reply-to email address of emails sent to the user.
   Default: empty


.. confval:: notification.adminEmail

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   E-Mail address(es) of website admin(s) who receive new/confirmed registrations.
   Multiple E-Mail addresses must be separated with a comma.


.. confval:: notification.registrationNew.userSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the user when a new registration is created.


.. confval:: notification.registrationWaitlistNew.userSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the user when a new registration on the waitlist is created.


.. confval:: notification.registrationNew.adminSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the admin when a new registration is created.


.. confval:: notification.registrationWaitlistNew.adminSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the admin when a new registration on the waitlist is created.


.. confval:: notification.registrationConfirmed.userSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the user when a registration has been confirmed.


.. confval:: notification.registrationWaitlistConfirmed.userSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the user when a registration on the waitlist has been confirmed.


.. confval:: notification.registrationConfirmed.adminSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the admin when a registration has been confirmed.


.. confval:: notification.registrationWaitlistConfirmed.adminSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the admin when a registration on the waitlist has been confirmed.


.. confval:: notification.registrationCancelled.userSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the user when a registration has been cancelled.


.. confval:: notification.registrationCancelled.adminSubject

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: Registration

   Subject of email sent to the admin when a registration has been cancelled.


Tab category menu
~~~~~~~~~~~~~~~~~

Categories Configuration
------------------------

.. confval:: categoryMenu.categories

   :Type: list of strings
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   A subset of categories that will be shown in the category menu. If empty, all categories will be shown.


.. confval:: categoryMenu.includeSubcategories

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Includes subcategories of selected categories in the category menu.


.. confval:: categoryMenu.orderField

   :Type: string
   :Default: title
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Order field for the category menu (internally limited to "title", "uid", and "sorting").


.. confval:: categoryMenu.orderDirection

   :Type: string
   :Default: asc
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup
   :Plugin: List, Search, Calendar

   Order direction for the category menu.
