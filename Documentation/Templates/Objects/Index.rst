.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _template_objects:

Template objects
================

The following objects are available in the different views.

Tip: You can use <f:debug>{object}</f:debug> in your template to see available properties of each object.

List view
~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {events}

   :Description:
         An object holding all events that matched the configured demand in the plugin settings

Detail view
~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

Registration view
~~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

Notification views
~~~~~~~~~~~~~~~~~~

.. t3-field-list-table::
 :header-rows: 1

 - :Object:
         Object:

   :Description:
         Description:

 - :Object:
         {event}

   :Description:
         An object holding the given event

 - :Object:
         {registration}

   :Description:
         An object holding the given registration

 - :Object:
         {settings}

   :Description:
         An array of extension settings

 - :Object:
         {hmac}

   :Description:
         HMAC for the registration UID

 - :Object:
         {reghmac}

   :Description:
         Appended HMAC for the registration UID

