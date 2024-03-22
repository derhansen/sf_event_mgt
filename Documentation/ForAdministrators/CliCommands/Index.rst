.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _cronjob:

============
CLI Commands
============

Cleanup expired registrations
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

*Only needed if you use registrations for events*

If a new participant registers to an event, the participant must confirm the registration in a
given timeframe (default 1 hour from registration time). If the participant does not confirms
the registration in the given timeframe, the booked place for the event should be made available
again for other participants.

In order to remove/hide expired registrations, a CLI command is available to remove/hide expired registrations.

Example with :command:`--delete` option::

  ./typo3/sysext/core/bin/typo3 sf_event_mgt:cleanup:expired --delete

Output:

.. figure:: /Images/command-cleanup-expired.png
   :alt: Cleanup expired command
   :class: with-shadow

It is recommended to setup a scheduler task to execute the CLI command periodically.

GDPR cleanup for registrations
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Local data privacy policies may require, that you only save personal user data
when needed. In order to remove personal user data of registrations including
saved registration field data for expired events, a CLI command is available.

**Arguments**

* `days` - Amount of days reduced from todays date for expired event selection.

**Options**

* `softDelete` - If set, registration will not be deleted hard, but only flagged as deleted
* `ignoreEventRestriction` - If set, simply all available registrations will be selected and deleted. Use with care!

Example::

  ./typo3/sysext/core/bin/typo3 sf_event_mgt:cleanup:gdpr 10

Output

.. figure:: /Images/command-cleanup-gdpr.png
   :alt: Cleanup GDPR command
   :class: with-shadow

It is recommended to setup a scheduler task to execute the CLI command periodically.

.. note::

   The GDPR cleanup only includes events, which have a start- and enddate. Events with no enddate are
   not covered by the cleanup, since it is not possible to calculate, when the event has ended.
