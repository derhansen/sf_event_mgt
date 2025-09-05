.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt
.. highlight:: typoscript

.. _userregistrationplugin-settings:

===========================
Frontend user registrations
===========================

The plugin is both used to output a list- and a detail view.

Nearly all important settings can be made through the plugin, which override the
settings made with TypoScript. All plugin settings can also be configured with TypoScript
(use :php:`plugin.tx_sfeventmgt.settings.` with the keys shown below).

.. important::
   Make sure,that you place the Plugin on a page with "Usergroup Access Rights" configured to only show
   the plugin output for logged in users and/or users belonging to a user group. Anyway, the plugin will only output
   content if there is an active frontend user session.

Tab settings
~~~~~~~~~~~~

Display mode
------------

.. confval:: userRegistration.displayMode

   :Type: string
   :Default: all
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   With this setting, the plugin can be configured to show registrations for **all** events, only
   **future** or only **past events**.

   Available options:

   - all
   - future
   - current_future
   - past
   - time_restriction


Sort by
-------

.. confval:: userRegistration.orderField

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Defines which field should be used for sorting events in the frontend.

   Possible values:

   - event.title
   - event.startdate
   - event.enddate

Sorting direction
-----------------

.. confval:: userRegistration.orderDirection

   :Type: string
   :Default: (none)
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Defines the sorting direction for `orderField`.

   Possible values:

   - (none)
   - asc
   - desc


Registration pid
----------------

.. confval:: registrationPid
   :name: user-registration-plugin-registration-pid1
   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Page where the event plugin is configured to show event registration.


Record storage page
-------------------

.. confval:: userRegistration.storagePage

   :Type: int or list of ints
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   One or more sysfolders where events and registrations are stored.


Recursive
---------

.. confval:: userRegistration.recursive

   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Recursion level for record storage page.

Tab additional
~~~~~~~~~~~~~~

Detail Page
-----------
.. confval:: detailPid
   :name: user-registration-plugins-registration-pid
   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Page where the plugin is configured to show event details.

Registration Page
-----------------

.. confval:: registrationPid
   :name: user-registration-plugin-registration-pid2
   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Page where the plugin is configured to show event registration.

Payment Page
------------
.. confval:: paymentPid
   :name: user-registration-plugin-payment-pid
   :Type: int
   :Default: 0
   :Path: plugin.tx_sfeventmgt.settings
   :Scope: Plugin, TypoScript Setup

   Page where the plugin is configured to handle payments for registration.
