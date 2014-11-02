.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _faq:

FAQ
===

Why do you not include a nice CSS stylesheet?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Because you normally customize templates/stylesheets to your needs. Therefore the
extension just comes with a rudementary CSS stylesheet available in
EXT:Resources/Public/Css/events_default.css which must be included manually like
shown below::

	page.includeCSS {
		events = EXT:Resources/Public/Css/events_default.css
	}

How do I export registered participants to a CSV file?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Please use the CSV export action as described in :ref:`backend-module` section.

How do I create a custom e-mail notification?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A detailled description can be found in the :ref:`custom-notifications` section.

Can I add the HMAC or an appended HMAC of the registration UID to e-mails?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, you can use the following objects in you e-mail templates

* {hmac} = HMAC of uid
* {reghmac} = appended uid+HMAC

Is it possible to extend events/registrations with own fields?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, just use the extension builder to extend sf_event_mgt with custom fields.


Is it possible to filter by categories in the listview
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, filtering of events by a category is possible if you pass the category to the listview.::

  <f:link.action action="list" controller="Event" arguments="{category: '{category.uid}'}">{category.title}</f:link.action>

This only works, if you create links with f:link.action as shown above. If you want to display the
categories in a select-box, then I suggest you create a CSS only select box (e.g. UL menu)
