.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _extconf:

Extension settings
==================

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Date type:
         Data type:

   :Description:
         Description:

   :Default:
         Default:

 - :Property:
         slugBehaviour

   :Date type:
         String

   :Description:
         uniqueInSite: The same slug can be used for news in different sites. Use this setting *only* if no
         event records are shared between sites. "unique" means that same event title will lead to different
         slug names.

   :Default:
         uniqueInSite

 - :Property:
         enableInvoice

   :Date type:
         Boolen

   :Description:
         Enable payment method 'invoice'

   :Default:
         true

 - :Property:
         enableTransfer

   :Date type:
         Boolen

   :Description:
         Enable payment method 'transfer'

   :Default:
         true

 - :Property:
         hideInlineRegistrations

   :Date type:
         Boolen

   :Description:
         Enable feature to hide registrations when editing an event in the backend

   :Default:
         False

- :Property:
         hideInlineRegistrationsLimit

   :Date type:
         Integer

   :Description:
         Max amount of registrations to display before event registrations will be hidden when editing an event.

   :Default:
         500
