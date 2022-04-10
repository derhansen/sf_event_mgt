.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _seositemap:

Seo Sitemap
===========

With TYPO3 9.5+ it is possible to generate a :ref:`t3coreapi:xmlsitemap` for extensions using the SEO extension included in
the TYPO3 core.

The example below shows a XML Sitemap configuration for sf_event_mgt::

  plugin.tx_seo.config {
    xmlSitemap {
      sitemaps {
        events {
          provider = TYPO3\CMS\Seo\XmlSitemap\RecordsXmlSitemapDataProvider
          config {
            table = tx_sfeventmgt_domain_model_event
            sortField = tstamp
            lastModifiedField = tstamp
            pid = <event-record-pid>
            recursive = 1
            url {
              pageId = <detail-pid>
              fieldToParameterMap {
                uid = tx_sfeventmgt_pieventdetail[event]
              }
              additionalGetParameters {
                tx_sfeventmgt_pieventdetail.controller = Event
                tx_sfeventmgt_pieventdetail.action = detail
              }
              useCacheHash = 1
            }
          }
        }
      }
    }
  }

Note, that you must replace :php:`<event-record-pid>` and :php:`<detail-pid>`  with your own values.

.. tip::
   When you only want to show future events, you can extend the config by :php:`additionalWhere = `tx_sfeventmgt_domain_model_event`.`enddate` > UNIX_TIMESTAMP()`
