.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _rssfeed:

RSS feed
========

This section describes how you add a RSS feed for the list of events. The implementation of the RSS feed in
sf_event_mgt is similar to the RSS feed implementation in the news extension from Georg Ringer. Many parts
of the RSS feed documentation are taken from his extension with small modifications to fit sf_event_mgt.

The template for the RSS feed can be found in the file :html:`Resources/Private/Templates/Event/List.xml`
To configure sf_event_mgt to use this template, simply set the format of the output als shown below:

.. code-block:: typoscript

   plugin.tx_sfeventmgt.settings.list.format = xml

RSS feed by TypoScript (recommended)
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

One way to generate the RSS feed is to use TypoScript. Add to following TypoScript to your site and adopt it to your
needs:

.. code-block:: typoscript

   [getTSFE().type == 9918]
   config {
     disableAllHeaderCode = 1
     xhtml_cleaning = none
     admPanel = 0
     debug = 0
     disablePrefixComment = 1
     metaCharset = utf-8
     additionalHeaders = Content-Type:application/rss+xml;charset=utf-8
     additionalHeaders.10.header = Content-Type:application/rss+xml;charset=utf-8
     absRefPrefix = {$plugin.tx_sfeventmgt.rss.channel.link}
   }

   pageEventRSS = PAGE
   pageEventRSS {
     typeNum = 9918
     10 < tt_content.list.20.sfeventmgt_pievent
     10 {
       settings < plugin.tx_sfeventmgt.settings
       settings {
         detailPid = 4
         storagePage = 3
         list {
           format = xml
         }
       }
     }
   }
   [global]

This example will show all events which are saved on the page with uid 3. The detail view page is the one with uid 4.

The RSS feed itself can be found with the link **/?type=9918**.

RSS feeds by using a plugin
^^^^^^^^^^^^^^^^^^^^^^^^^^^

If you want to use the sf_event_mgt plugin to configure the settings of your RSS feed, then just configure the
event plugin as if you are creating a list view (set startin point, categories, detail page, ...)

Next, add a new TypoScript template to the page and insert the following TypoScript to the setup section

.. code-block:: typoscript

   page = PAGE
   page.10 < styles.content.get

   config {
      disableAllHeaderCode = 1
      xhtml_cleaning = none
      admPanel = 0

      metaCharset = utf-8
      additionalHeaders = Content-Type:application/rss+xml;charset=utf-8
      additionalHeaders.10.header = Content-Type:application/rss+xml;charset=utf-8
      disablePrefixComment = 1
   }

   # set the format
   plugin.tx_sfeventmgt.settings.list.format = xml

   # delete content wrap
   tt_content.stdWrap >
   tt_content.stdWrap.editPanel = 0

**Important**
If the sf_event_mgt plugin is located in different column than default (0), then you extend the TypoScript as
following:

.. code-block:: typoscript

   page.10.select.where = colPos=1

.. note::

   When using Fluid Styled Content, you have to make sure, that you override layouts and templates of
   Fluid Styled Content in order to remove the wrapper-div and also to ensure the RSS feed contains
   no empty lines. To keep things simple, it is recommended to configure the RSS feed using TypoScript

RSS feed configuration
""""""""""""""""""""""

Don't forget to configure the RSS feed properly as the sample template won't fulfill your needs completely.
Please look up the constants and change the mentioned settings.

.. code-block:: typoscript

   plugin.tx_sfeventmgt {
       rss.channel {
           title = Feed title
           description =
           link = http://domain.tld/
           language = en-gb
           copyright = TYPO3 Event management and registration
           category =
           generator = TYPO3 EXT:sf_event_mgt
       }
   }

Add a link to the RSS feed in the list view
"""""""""""""""""""""""""""""""""""""""""""

To be able to render a link in the header section of the normal page which points to the RSS feed you can use
something like this in your List.html fluid template.

.. code-block:: html

    <e:headerData>
        <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{f:uri.page(additionalParams:{type:9918})}" />
    </n:headerData>
