.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _installation:

Installation
============

The installation and initial configuration of the extension is as following:

#. Install the extension with the extension manager

#. Include the static TypoScript configuration “Event management and registration (sf\_event\_mgt)” in your TypoScript template

#. Create a new sysfolder in your page tree, where you create events and categories.

#. Depending on your needs, create a TYPO3 page for event listing, event details and event registration.

#. Add the plugin :ref:`eventplugin-settings` to the newly created pages and configure the plugin settings.

#. Configure extension TypoScript settings depending on your needs.

#. Optionally add the plugin :ref:`userregistrationplugin-settings` to a page, to show registered frontend users their event registrations

Important
~~~~~~~~~

#. If you use **registrations for events**, you must follow the instructions regarding the :ref:`cronjob`

#. If you want TYPO3 editors (not admins) to be able to export registrations as a **CSV file**, those users must have a read/write access to the default upload folder (or ``options.defaultUploadFolder`` in User TSconfig)

#. For the **calendar view**, make sure to uncheck the **Disable overwrite demand** setting in the plugin