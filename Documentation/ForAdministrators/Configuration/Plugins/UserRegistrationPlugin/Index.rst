.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../../Includes.txt


.. _userregistrationplugin-settings:

Events and event-registration - FE user registrations
=====================================================

**NOTE:** Make sure,that you place the Plugin on a page with "Usergroup Access Rights" configured to only show
the plugin output for logged in users and/or users belonging to a user group. Anyway, the plugin will only output
content if there is an active FE user session.

Settings
~~~~~~~~

Nearly all important settings can be made through the plugin, which override the
settings made with TypoScript. All plugin settings can also be configured with TypoScript
(use ``plugin.tx_sfeventmgt.settings.`` with the keys shown below).

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
         Display mode

   :View:
         List

   :Description:
         With this setting the plugin can be configured to show registrations for **all** events, only
         **future** or only **past events**.

         Available options

         * all
         * future
         * current_future
         * past

   :Key:
         userRegistration.displayMode

 - :Property:
         Sort by

   :View:
         List

   :Description:
         Defines which field should be used for sorting events in the frontend. The default sorting field is
         "startdate", which can be overridden by using this setting.

   :Key:
         userRegistration.orderField

 - :Property:
         Sorting direction

   :View:
         List

   :Description:
         Defines the sorting direction for orderField. The default sorting direction is
         "asc", which can be overridden by using this setting.

         Possible values:

         * <empty value>
         * asc
         * desc

   :Key:
         userRegistration.orderDirection

 - :Property:
         Registration pid

   :View:
         List

   :Description:
         Page, where the event plugin is configured to show event registration

   :Key:
         registrationPid

 - :Property:
         Record storage page

   :View:
         List

   :Description:
         One or more sysfolders, where events and registrations are stored

   :Key:
         userRegistration.storagePage

 - :Property:
         Recursive

   :View:
         List

   :Description:
         Recursion level for record storage page

   :Key:
         userRegistration.recursive
