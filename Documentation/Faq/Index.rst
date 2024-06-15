.. include:: /Includes.rst.txt

.. highlight:: typoscript
.. _faq:

===
FAQ
===

.. rst-class:: panel panel-default

The event detail page shows a 404 error
=======================================

This problem can occur in TYPO3 versions greater than 9.5.17 or 10.4.2 when the TYPO3 website has multiple
sites that use a shared folder for events. If this is the case, you must configure :php:`unique` slug handling
in the extension settings :php:`slugBehaviour`.

.. rst-class:: panel panel-default

Why do you not include a nice CSS stylesheet?
=============================================

Because you normally customize templates/stylesheets to your needs. Therefore the
extension just comes with a rudementary CSS stylesheet available in
:php:`EXT:Resources/Public/Css/events_default.css` which must be included manually like
shown below::

	page.includeCSS {
	        events = EXT:sf_event_mgt/Resources/Public/Css/events_default.css
	}

.. rst-class:: panel panel-default

Is it possible to extend events/registrations with own fields?
==============================================================

Yes, since version 3.0 of the extension, you can add additional :ref:`registrationfield` on event basis.

You can also extend sf_event_mgt with own fields (e.g. new fields for Event). I have created a demo extension,
which shows how to add new fields to the event and registration domain model.

Demo Extension: https://github.com/derhansen/sf_event_mgt_extend_demo

The extension contains a short manual (README.md) how to add your own fields to existing domain models.

.. rst-class:: panel panel-default

How do I export registered participants to a CSV file?
======================================================

Please use the CSV export action as described in :ref:`backend-module` section.

.. rst-class:: panel panel-default

How do I create a custom email notification?
=============================================

A detailed description can be found in the :ref:`custom-notifications` section.

.. rst-class:: panel panel-default

How do I disable specific email notifications?
=============================================

Email notifications will not be sent if the subject is empty. Since the extension provides default subjects, you need to delete them as shown below::

 plugin.tx_sfeventmgt {
   settings {
     notification {
       registrationNew {
	 adminSubject >
       }
     }
   }
 }

In this example, admin notifications for new registrations are disabled. Please refer to section :ref:`tsref` for available options.

Remember that you can also overwrite subjects in the plugin. Make sure that the corresponding subject of a notification is also empty in the plugin.

.. rst-class:: panel panel-default

Can I add the HMAC or an appended HMAC of the registration UID to emails?
=========================================================================

Yes, you can use the following objects in you email templates

* :php:`{hmac}` = HMAC of uid
* :php:`{reghmac}` = appended uid+HMAC

.. rst-class:: panel panel-default

Is it possible to filter by categories in the listview
======================================================

Yes, filtering of events by a category is possible if you pass the category to the overwriteDemand setting of the listview.::

 <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{category: category}}">{category.title}</f:link.action>

This only works, if you create links with :php:`f:link.action` as shown above. If you want to display the
categories in a select-box, then I suggest you create a CSS only select box (e.g. UL menu)

.. rst-class:: panel panel-default

When does {event.registrationPossible} return TRUE
==================================================

For each event, the attribute registrationPossible returns TRUE or FALSE, if registration for
the event is possible. TRUE is returned, when all conditions below are fulfilled:

* Registration option is activated for the event
* Max participants is not reached (if max. participants > 0) or max participants is not reached and waitlist is enabled
* Date set at registration deadline is not reached
* Startdate of event is not reached
* Startdate of registration is reached (if set)

..  _recurring-events:

.. rst-class:: panel panel-default

Why does the extension not support recurring events?
===================================================
The user registration is one of the main features of the extension and it requires, that every event is unique in order
to save registrations for a particular event. This makes it impossible to only have one event record, that has multiple
recurrences.

Since there is no smart way to add recurring events to the extension without making it more complex and harder to
maintain, this feature will not make it into the extension.

.. rst-class:: panel panel-default

How can I disable double opt-in for event registration?
=======================================================

You can enable autoConfirmation for new registrations as described in the :ref:`tsref` section.
With autoConfirmation enabled, new registrations will automatically be confirmed and the user
will not receive a confirmation email.

.. rst-class:: panel panel-default

How does the simultaneous registration process work?
====================================================

If the field "Max. simultaneous registrations per user" is set to a value greater than 1 for the given
event, a user is able to create multiple registrations at once. If the user in the registration view
chooses to create more than one registration, there will be created the given amount of registrations
in the backend for the user. All fields (e.g. firstname, lastname, email) will contain the same values.

The first registration saved is the "main registration", and all other registrations saved later will
depend on the "main registration". All "dependent registrations" will automatically get the option
"No email notifications" set to true, so custom notifications are only sent to the email address
of the "main registration".

If automatic confirmation is turned off (default), the user has to confirm the registration by clicking
a link in the confirmation email. When the user confirms the registration, all "dependent registrations"
of the "main registration will automatically also be confirmed.

.. rst-class:: panel panel-default

How can I set a default currency?
=================================

You can set default values for many fields in TYPO3 by using TCAdefaults. To set a default value for the
currency field, add the following to the Page TSConfig, which sets € as the default currency for new records::

 TCAdefaults {
   tx_sfeventmgt_domain_model_event.currency = €
 }

.. rst-class:: panel panel-default

How to make the field "Accept terms and conditions" mandatory
=============================================================

The field "Accept terms and conditions" is part of the registration domain model, but it is not required
during registration. To make the field required, add the field to the list of required field like shown below::

 plugin.tx_sfeventmgt {
   settings {
     registration {
       requiredFields = accepttc
     }
   }
 }

.. rst-class:: panel panel-default

How do I add my own custom translations
=======================================

You can override all language files with your own translations/labels. As an example, the following code
overrides/extends the :php:`locallang_db.xlf` and the :php:`locallang.xlf`

Add this example code to a :php:`ext_localconf.php` file (e.g. in a site package extension).::

 $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf'][] = 'EXT:your_ext/Resources/Private/Language/de.custom_locallang_db.xlf';
 $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf'][] = 'EXT:your_ext/Resources/Private/Language/de.custom_locallang.xlf';

.. rst-class:: panel panel-default

How can I use the overwriteDemand feature for the search view
=============================================================

It is also possible to use the overwriteDemand feature for the search view in order to limit the
events that the search result includes. If you for example wish to limit the search to a special
category, you must pass the category UID as shown below (the value field contains the category UID).::

 <f:form.textfield name="overwriteDemand[category]" value="1"/>

.. rst-class:: panel panel-default

How can I add pagination to the listview?
=========================================

Since version 6.0 of the extension, the list view uses the TYPO3 pagination API to provide
necessary objects required.::

 <f:for each="{pagination.paginator.paginatedItems}" as="event" iteration="iterator">
     <f:render partial="Event/ListItem" arguments="{_all}"/>
 </f:for>

.. rst-class:: panel panel-default

How can I add pagination to the searchview?
===========================================

Native pagination is not supported for the searchview, since besides GET parameters
also POST parameters need to be considered in order to render the pagination. Although
it technically would be possible to implement this feature, it will not be includes
in the extension as it is a suboptimal solution (search word as dynamic GET parameter).
If you need a paginated search for events, it is recommended to use a search extension
(e.g. ext:ke_search or ext:solr).

.. rst-class:: panel panel-default

How does the payment process work
=================================

For each event with registration enabled, you can also enable payment. If payment is enabled, you can output
available payment methods for the event in the registration form. When a user registers for an event, he
can select a payment method.

The extension comes with 2 default payment methods :php:`debit` and :php:`transfer`. Both payment methods do not include
any further payment processing.

It is possible to extend the extension with own payment methods that include further payment processing (e.g. by
an external payment provider).

For more information on how to add custom payment methods, see :ref:`developer_payment` section

.. rst-class:: panel panel-default

The default payment methods are missing
=======================================

Open the extension settings in the extension manager and press the "Save" button.

.. rst-class:: panel panel-default

Configured price options do not show up in frontend
===================================================

Make sure that the date for the price option is valid. Also make sure, that you use :php:`{event.currentPrice}` in your
Fluid template to output the current price.

.. rst-class:: panel panel-default

How can I use the iCalDownload action in the Listview?
======================================================

With the following Fluid snippet, you can use the iCalDownload in the listview::

 <f:link.action action="icalDownload" arguments="{event : event}" pageUid="{settings.detailPid}"><f:translate key="event.icalDownload" /></f:link.action>

Note, that you have to set the pageUid to a page with the detail view plugin.

.. rst-class:: panel panel-default

Why does the next/previous month links not work for the calendar view?
=======================================================================

The next/previous links use the :php:`overwriteDemand` feature, which by default is disabled. Make sure you have
unchecked the **Disable overwrite demand** setting in the plugin.

.. rst-class:: panel panel-default

The category filter for the list view does not work
===================================================

The filtering also uses the :php:`overwriteDemand` feature, which by default is disabled. Make sure you have
unchecked the **Disable overwrite demand** setting in the plugin and also ensure that the category mode is not
equal to :php:`Ignore category selection`.

.. rst-class:: panel panel-default

How do I show the event title as page title on the detail page?
===============================================================

Either use the TYPO3 PageTitle API or you can use the title-ViewHelper of this extension.

.. rst-class:: panel panel-default

How do I set the indexed search title for an event?
===================================================

Use the title-ViewHelper of this extension.

.. rst-class:: panel panel-default

The Payment Plugin throws exception about missing default controller
====================================================================

The page with the Payment Plugin shows the following error::

 The default controller for extension "SfEventMgt" and plugin "Pipayment" can not be determined. Please check for TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin() in your ext_localconf.php

Please delete the content element with the Payment Plugin and create a blank content element of type "Plugin" and
next directly select the Payment Plugin from the plugins select box.

If the plugin originally was a plugin with Flexform settings and switchableControllerActions, those settings
will remain in the database field pi_flexform and lead to the described error.

.. rst-class:: panel panel-default

How can I move registrations on the waitlist automativally up, if a registered user cancels a registration?
==============================================================================================================

Since version 5.2.0 there is a simple and default waitlist move up process. Please refer to the documentation
section about the :ref:`waitlist_moveup` for further information.

If the default move up process does not fulfill your needs, you can use the PSR-14 Event :php:`WaitlistMoveUpEvent`
to implement your own move up logic.

.. rst-class:: panel panel-default

Event registrations get confirmed by search engines
===================================================
Under certain conditions with extensions that create sitemaps it may happen, that a confirmation link of a
registration email gets added to the sitemap and afterwards visited by a search engine crawler.
This behavior has at least been seen when the extension :php:`metaseo` has been used to create a sitemap.

In order to avoid the registration link from being added to the sitemap, the page with the
registration plugin needs to be excluded from the sitemap, i.e. use "Exclude from sitemap" in the page
settings or by other means (e.g. blacklist in EXT:metaseo).

Use https://github.com/derhansen/sf_event_mgt/issues/597 for further details/discussion.

.. rst-class:: panel panel-default

Displaying events using the "Insert Record" content element
===========================================================

If you display events using the "Insert Record" content element, you may want to use a different layout to display
the event detail view. For this purpose, you can use :php:`{settings.detail.isShortcut}` in the Detail.html Fluid
Template to render a different layout.

.. rst-class:: panel panel-default

How can I display JSON-LD data for events?
==========================================
If you want to to display JSON-LD data for events in the event detail view, you can add an own partial including
the JSON-LD data you need. Since requirements of data in JSON-LD may vary per project, sf_event_mgt does not ship
with a partial or ViewHelper to create such data.

Example Partial Code (will work until Fluid < 3.0)::

 <script type="application/ld+json">
    <f:format.raw>{</f:format.raw>
      "@context": "https://schema.org/",
      "@type": "Event",
      "name": "{event.title}",
      "startDate": "<f:format.date format="Y-m-d">{event.startdate}</f:format.date>",
      "location": <f:format.raw>{</f:format.raw>
        "@type": "Place",
        "name": "{event.location.title}",
        "address": <f:format.raw>{</f:format.raw>
          "@type": "PostalAddress",
          "streetAddress": "{event.location.address}",
          "addressLocality": "{event.location.city}",
          "postalCode": "{event.location.zip}",
          "addressCountry": "{event.location.country}"
          <f:format.raw>}</f:format.raw>
          <f:format.raw>}</f:format.raw>,
      "image": [
        <f:if condition="{event.image}">
        "<f:uri.image image="{event.image.0}" absolute="true"/>"
        </f:if>
       ],
      "description": "<f:format.raw>{event.description}</f:format.raw>",
      "endDate": "<f:format.date format="Y-m-d">{event.enddate}</f:format.date>",
      "offers": <f:format.raw>{</f:format.raw>
        "@type": "Offer",
        "url": "<f:uri.action action="registration" absolute="true" arguments="{event : event}" pageUid="{settings.registrationPid}"></f:uri.action>",
        "price": "{event.currentPrice}",
        "priceCurrency": "{event.currency}",
        "availability": "https://schema.org/InStock",
      <f:format.raw>}</f:format.raw>,
        "performer": <f:format.raw>{</f:format.raw>
          "@type": "PerformingGroup",
          "name": "{event.organisator.name}"
      <f:format.raw>}</f:format.raw>
   <f:format.raw>}</f:format.raw>
 </script>

**Note:** When using Fluid, always make sure to escape variables properly.

As an alternative, you could create an own ViewHelper which generates the required JSON-LD data.

.. rst-class:: panel panel-default

Images for categories are not shown in the frontend?
====================================================
Categories in TYPO3 backend do not have an image by default. The TYPO3 extension ext:news
by Georg Ringer adds additional fields (e.g. an image-field) to the category domain model.

If you want ext:sf_event_mgt to use the category domain model of ext:news, the category domain model
of ext:sf_event_mgt needs to be overridden as shown below:

Add this to an extension (e.g. your sitepackage) in :php:`ext_localconf.php`::

 GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
     ->registerImplementation(
         \DERHANSEN\SfEventMgt\Domain\Model\Category::class,
         \GeorgRinger\News\Domain\Model\Category::class
     );

After adding this snippet and clearing the cache, all categories in events do now use the category
domain model of ext:news

.. rst-class:: panel panel-default

Why is a registration link shown even if the registration dealine expired?
==========================================================================
Make sure that you configured the page cache settings as described in :ref:`pagecache`

.. rst-class:: panel panel-default

Editing events is very slow having a huge amount of registrations. Can this be fixed?
=====================================================================================
Short answer: No, not really. For TCA inline fields, TYPO3 will load all data before opening the records
in the backend. So having an event with 1500 registrations will actually load all registrations before
showing the edit form for the event in the TYPO3 backend. Note, that just disabling the field "registration"
by TCA will not work, since TYPO3 will load the data anyway.

In order to make it at least possible to edit the event data, the extension makes it possible to hide all
registration inline fields and prevent TYPO3 from loading all data when a configurable limit of registrations
is reached per event.

Please refer to section :ref:`_extconf` for available options.

.. rst-class:: panel panel-default

How can I show and browse the event calendar by weeks?
======================================================

The calendar view is able to show events by week or by month. The default template
contains the markup for the month view including links to browse to the previous
and next month.

In order to show events by week, it is recommended to create a new template layout
for the calendar view and next to use the fluid variable :php:`{weekConfig}` to
show events for the current week and to create previous and next week links. The
default template includes an example, which is surrounded by :php:`<f:comment>`

How can I preview event records while editing?
==============================================

It is possible to activate the :guilabel:`View` Button for event records.

Add the following page TSconfig to your root page. It is recommended to use
a site package extension, see
:doc:`Official Tutorial: Site Package <t3sitepackage:Index>` for this purpose.

.. code-block:: typoscript

   TCEMAIN {
       preview {
           tx_sfeventmgt_domain_model_event {
               previewPageId = 91
               useDefaultLanguageRecord = 0
               fieldToParameterMap {
                   uid = tx_sfeventmgt_pieventdetail[event_preview]
               }
               additionalGetParameters {
                   tx_sfeventmgt_pieventdetail.controller = Event
                   tx_sfeventmgt_pieventdetail.action = detail
               }
           }
       }
   }

By using the given example, a link will be generated which leads to the
page with the id `91`.

If a event detail plugin is placed on this page, the event will be shown.
If hidden events should be shown, make sure to activate the
`Allow preview of hidden event records` setting in the detail plugin.

.. note::

  Be aware to secure the page (e.g. set page visibility to "hidden") as this page could be called by anyone
  using any event record uid to see its content.

.. note::

  For security reasons, please always use a dedicated page with a dedicated event detail plugin for event preview.

Registrations get confirmed and/or cancelled by Email Security Gateways
=======================================================================

Some Email Security Gateways or Antivirus/Anti-Malware Software may follow links in emails. This can lead to the
situation, that event registrations get confirmed and/or cancelled automatically.

The TypoScript settings :php:`settings.confirmation.additionalVerificationStep` and
:php:`settings.cancellation.additionalVerificationStep` can be activated to avoid this problem. When active,
links in emails will refer to a page, where an confirmation- or cancellation-link has to be clicked manually
by the user.