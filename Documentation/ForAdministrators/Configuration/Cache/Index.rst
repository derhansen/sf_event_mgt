.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _pagecache:

Page Cache Configuration
========================

Since TYPO3 does not know, which records a plugin will show, it can not automatically consider starttime/stoptime
of e.g. event records for the page cache lifetime calculation. It has to be configured that TYPO3 will include the
starttime/stoptime of records in the page cache lifetime calculation using :php:`config.cache` as described in the
`TypoScript reference <https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Setup/Config/Index.html#cache>`__

Example::

    config.cache.3 = tx_sfeventmgt_domain_model_event:2
    config.cache.4 = tx_sfeventmgt_domain_model_event:2

The shown example will include the starttime/stoptime for all events in PID 2 (storage page for events) for cache
lifetime calculation of PID 3 (page with list view plugin) and 4 (page with detail view plugin).

When you use "Event management and registration" along with the *Registration start date* or *Registration dealine*
featurem it is **highly recommended** to configure cache settings like shown in the example above. If this setting is not
configured properly, the cache lifetime of a page may be too high resulting in registration links being shown for
events, where registration is not logically possible.

The extension hooks into the page cache lifetime calculation and uses the configured cache configuration to calculate
the cache lifetime for events with a *Registration start date* or *Registration dealine*. Please note, that the
calculation only includes events, where the startdate is not reached and where registration is enabled.
