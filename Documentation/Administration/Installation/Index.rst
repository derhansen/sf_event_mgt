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

#. Include the plugin “Event management” the newly created pages and configure the plugin settings.

#. Configure extension TypoScript settings depending on your needs.

Important
~~~~~~~~~

If you use **registrations for events**, you must follow the instructions regarding :ref:`clearcacheuids` and :ref:`cronjob`
