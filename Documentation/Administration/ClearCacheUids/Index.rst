.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _clearcacheuids:

Clear Cache Uids
================

**Only needed if you use registrations for events**

The list- and detail-view of the extension runs in cached mode, so page load time is
not affected by the extension. If you use the extension in combination with the
registration option for events, it is nescessary to configure, which pages need to
cleared in the cache, if a new registration for an event is created.

So if you use the registration option, please configure the following TypoScript setting::

    plugin.tx_sfeventmgt {
        settings {
            clearCacheUids = pid1,pid2
        }
    }

Make sure, you configure this setting at **template root level**