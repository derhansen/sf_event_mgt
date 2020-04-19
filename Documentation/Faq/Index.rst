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
	        events = EXT:sf_event_mgt/Resources/Public/Css/events_default.css
	}

Is it possible to extend events/registrations with own fields?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, since version 3.0 of the extension, you can add additional :ref:`registrationfield` on event basis.

You can also extend sf_event_mgt with own fields (e.g. new fields for Event). I have created a demo extension,
which shows how to add new fields to the event and registration domain model.

Demo Extension: https://github.com/derhansen/sf_event_mgt_extend_demo

The extension contains a short manual (README.md) how to add your own fields to existing domain models.

How do I export registered participants to a CSV file?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Please use the CSV export action as described in :ref:`backend-module` section.

How do I create a custom email notification?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A detailed description can be found in the :ref:`custom-notifications` section.

Can I add the HMAC or an appended HMAC of the registration UID to emails?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, you can use the following objects in you email templates

* {hmac} = HMAC of uid
* {reghmac} = appended uid+HMAC

Is it possible to filter by categories in the listview
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Yes, filtering of events by a category is possible if you pass the category to the overwriteDemand setting of the listview.::

 <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{category: category}}">{category.title}</f:link.action>

This only works, if you create links with f:link.action as shown above. If you want to display the
categories in a select-box, then I suggest you create a CSS only select box (e.g. UL menu)

When does {event.registrationPossible} return TRUE
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For each event, the attribute registrationPossible returns TRUE or FALSE, if registration for
the event is possible. TRUE is returned, when all conditions below are

* Registration option is activated for the event
* Max participants is not reached (if max. participants > 0) or max participants is not reached and waitlist is enabled
* Date set at registration deadline is not reached
* Startdate of event is not reached

Why does the extenion not support recurring events?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
The user registration is one of the main features of the extension and it requires, that every event is unique in order
to save registrations for a particular event. This makes it impossible to only have one event record, that has multiple
recurrences.

Since there is no smart way to add recurring events to the extension without making it more complex and harder to
maintain, this feature will not make it into the extension.

How can I disable double opt in for event registration?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can enable autoConfirmation for new registrations as described in the :ref:`tsref` section.
With autoConfirmation enabled, new registrations will automatically be confirmed and the user
will not receive a confirmation email.

How does the simultaneous registration process work?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

How can I set a default currency?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can set default values for many fields in TYPO3 by using TCAdefaults. To set a default value for the
currency field, add the following to the Page TSConfig, which sets € as the default currency for new records::

 TCAdefaults {
   tx_sfeventmgt_domain_model_event.currency = €
 }


How to make the field "Accept terms and conditions" mandatory
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The field "Accept terms and conditions" is part of the registration domain model, but it is not required
during registration. To make the field required, add the field to the list of required field like shown below::

 plugin.tx_sfeventmgt {
   settings {
     registration {
       requiredFields = accepttc
     }
   }
 }

How do I add my own custom translations
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can override all language files with your own translations/labels. As an example, the following code
overrides/extends the ``locallang_db.xlf`` and the ``locallang.xlf``

Add this example code to a ``ext_localconf.php`` file (e.g. in a site package extension).::

 $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:sf_event_mgt/Resources/Private/Language/locallang_db.xlf'][] = 'EXT:your_ext/Resources/Private/Language/de.custom_locallang_db.xlf';
 $GLOBALS['TYPO3_CONF_VARS']['SYS']['locallangXMLOverride']['de']['EXT:sf_event_mgt/Resources/Private/Language/locallang.xlf'][] = 'EXT:your_ext/Resources/Private/Language/de.custom_locallang.xlf';


How can I use the overwriteDemand feature for the search view
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

It is also possible to use the overwriteDemand feature for the search view in order to limit the
events that the search result includes. If you for example wish to limit the search to a special
category, you must pass the category UID as shown below (teh value field contains the category UID).::

 <f:form.textfield name="overwriteDemand[category]" value="1"/>


How can I add pagination to the listview?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can use the Paginate ViewHelper that comes with TYPO3 Fluid. Below follows example code which
should be added to the List-Template.::

 <f:widget.paginate objects="{events}" as="paginatedEvents" configuration="{itemsPerPage: 5, insertAbove: 1, insertBelow: 1, maximumNumberOfLinks: 10, addQueryStringMethod: 'POST,GET'}">
     <f:for each="{paginatedEvents}" as="event">
         <f:render partial="Event/ListItem" arguments="{_all}"/>
     </f:for>
 </f:widget.paginate>


How does the payment process work
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For each event with registration enabled, you can also enable payment. If payment is enabled, you can output
available payment methods for the event in the registration form. When a user registers for an event, he
can select a payment method.

The extension comes with 2 default payment methods "debit" and "transfer". Both payment methods do not include
any further payment processing.

It is possible to extend the extension with own payment methods that include furter payment processing (e.g. by
an external payment provider).

For more information on how to add custom payment methods, see :ref:`developer_payment` section

The default payment methods are missing
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open the extension settings in the extension manager and press the "Save" button.

Configured price options do not show up in frontend
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Make sure that the date for the price option is valid also make sure, that you use ``{event.currentPrice}`` in your
Fluid template to output the current price.

How can I use the iCalDownload action in the Listview?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The switchableControllerAction for the ListView does not allow to call the iCalDownload action. With the following
Fluid snippet, you can also use the iCalDownload in the listview::

 <f:link.action action="icalDownload" arguments="{event : event}" pageUid="{settings.detailPid}"><f:translate key="event.icalDownload" /></f:link.action>

Note, that you have to set the pageUid to a page with the detail view plugin.

Why does the next/previous month links not work for the calendar view ?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The next/presious links use the ``overwriteDemand`` feature, which by default is disabled. Make sure you have
unchecked the **Disable overwrite demand** setting in the plugin.

How do I show the event title as page title on the detail page?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can either use a SEO extension (e.g. ext:cs_seo), which has a custom and configurable page title rendering
function or you can use the title-ViewHelper of this extension.

How do I set the indexed search title for an event?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Use the title-ViewHelper of this extension.

The Payment Plugin throws exception about missing default controller
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The page with the Payment Plugin shows the following error::

 The default controller for extension "SfEventMgt" and plugin "Pipayment" can not be determined. Please check for TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin() in your ext_localconf.php

Please delete the content element with the Payment Plugin and create a blank content element of type "Plugin" and
next directly select the Payment Plugin from the plugins select box.

If the plugin originally was a plugin with Flexform settings and switchableControllerActions, those settings
will remain in the database field pi_flexform and lead to the described error.

How do can I move registrations on the waitlist automativally up, if a registered user cancels a registration?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You should use the PSR-14 Event ``WaitlistMoveUpEvent``.

Images and/or image attributes do not get translated
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Translation of FAL object in Extbase is broken in TYPO3 up to version 8.7. Please refer to the following forge issue
and use ext:repair_translation.

Forge issue: https://forge.typo3.org/issues/57272
Extension - Repair Translation: https://github.com/froemken/repair_translation

Event registrations get confirmed by search engines
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
Under certain conditions with extensions that create sitemaps it may happen, that a confirmation link of a
registration email gets added to the sitemap and afterwards visited by a search engine crawler.
This behavior has at least been seen when the extension ``metaseo`` has been used to create a sitemap.

In order to avoid the registration link from being added to the sitemap, the page with the
registration plugin needs to be excluded from the sitemap, i.e. use "Exclude from sitemap" in the page
settings or by other means (e.g. blacklist in EXT:metaseo).

Use https://github.com/derhansen/sf_event_mgt/issues/597 for further details/discussion.

Displaying events using the "Insert Record" content element
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If you display events using the "Insert Record" content element, you may want to use a different layout to display
the event detail view. For this purpose, you can use ``{settings.detail.isShortcut}`` in the Detail.html Fluid
Template to render a different layout.

How can I display JSON-LD data for events?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
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

Is it required to save the sys_language_uid for registrations?
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Long story short: Yes, it is.

In order to make limitations (e.g. max. participants) for events to work, the extension
always saves events in the default language to registrations. This is a workaround, but
as fas as I know the only way to have one central place to access total amount of
registrations for an event.

The correct sys_language_uid in the registration object is required to make Extbase
fetch the translated event object when accessing the registration object.

When the sys_language_uid is missing, wrong or -1, $registrationRepository->getByUid($uid)
will return the expected registration object, but the getEvent() method returns the
wrong (non translated) event, so e.g. translated emails will not show the translations
for the event.

When fetching the event using a custom findBy method using the Extbase query builder
will return the translated event, but only if $registrationRepository->getByUid($uid)
has not been called before. So this is no option.

In order to close to the TYPO3 core and not to implement more workarounds for translation
issues, it is best to save registrations and registration fields with the current
sys_language_uid set for frontend requests.