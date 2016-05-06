.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _breakingchanges:

Breaking Changes
================

1.5.0
~~~~~

The removal of the local category system requires the execution of a migration script, so existing
categories will get migrated to sys_category entries. Please use the update script in the extension
manager to process the migration as shown below.

.. figure:: ../Images/ext-update-category.png
   :align: left
   :width: 147px
   :alt: Extension Update Icon

Please click on the update-icon to start the category migration.


1.2.0
~~~~~

Due to the new cancellation-option for registrations, you need update all plugins, which are
configured to display "Registration" in the "What to display" section. Just open the plugin for edit
and select "Registration" in the "What to display"-selectbox.
