.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _installation:

============
Installation
============

.. rst-class:: bignums-tip

#. Install this extension

   - Composer mode: :php:`composer require derhansen/sf_event_mgt`
   - Classic mode: Install the extension with the extension manager

#. Configuration

   - Include the static TypoScript configuration :guilabel:`Event management and registration (sf\_event\_mgt)` in your TypoScript template
   - Configure extension TypoScript settings depending on your needs.

#. Create initial content

   - Create a new sysfolder in your page tree, where you create events and categories.
   - Add the plugin :ref:`eventplugin-settings` to the newly created pages and configure the plugin settings.
   - Optionally add the plugin :ref:`userregistrationplugin-settings` to a page, to show registered frontend users their event registrations

.. important::

   #. If you use **registrations for events**, you must follow the instructions regarding the :ref:`cronjob`

   #. For the **calendar view**, make sure to uncheck the **Disable overwrite demand** setting in the plugin
