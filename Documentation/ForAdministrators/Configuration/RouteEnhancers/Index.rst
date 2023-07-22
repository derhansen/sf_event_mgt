.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _route_enhancers:

Route Enhancers
===============

Since TYPO3 9.5, the TYPO3 Core comes with speaking URLs out of the box. For Extbase Extensions, the administrator
can configure route enhancers to create speaking URLs for extension parameters.

The following example shows a basic configuration for routes of sf_event_mgt.

.. note::

   The examples shown are for version 6.x of the extension, where several new plugins
   have been introduced. For an example routing configuration that covers version 4.x and 5.x
   of the extension, please switch to the version of the documentation to the left.

Configuration::

    routeEnhancers:
      EventListPlugin:
        type: Extbase
        limitToPages: [21]
        extension: SfEventMgt
        plugin: Pieventlist
        routes:
          -
            routePath: /
            _controller: 'Event::list'
          -
            routePath: '/location/{location_title}'
            _controller: 'Event::list'
            _arguments:
              location_title: overwriteDemand/location
          -
            routePath: '/speaker/{speaker_name}'
            _controller: 'Event::list'
            _arguments:
              speaker_name: overwriteDemand/speaker
          -
            routePath: '/organisator/{organisator_name}'
            _controller: 'Event::list'
            _arguments:
              organisator_name: overwriteDemand/organisator
          -
            routePath: '/category/{category_title}'
            _controller: 'Event::list'
            _arguments:
              'category_title': 'overwriteDemand/category'
        defaultController: 'Event::list'
        aspects:
          location_title:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_location
            routeFieldName: slug
          speaker_name:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_speaker
            routeFieldName: slug
          organisator_name:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_organisator
            routeFieldName: slug
          category_title:
            type: PersistedAliasMapper
            tableName: 'sys_category'
            routeFieldName: 'slug'
      EventDetailPlugin:
        type: Extbase
        limitToPages: [22]
        extension: SfEventMgt
        plugin: Pieventdetail
        routes:
          -
            routePath: '/{event_title}'
            _controller: 'Event::detail'
            _arguments:
              event_title: event
          -
            routePath: '/{event_title}/ical'
            _controller: 'Event::icalDownload'
            _arguments:
              event_title: event
        defaultController: 'Event::detail'
        aspects:
          event_title:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_event
            routeFieldName: slug
      EventRegistrationPlugin:
        type: Extbase
        limitToPages: [23]
        extension: SfEventMgt
        plugin: Pieventregistration
        routes:
          -
            routePath: '/{event_title}'
            _controller: 'Event::registration'
            _arguments:
              event_title: event
          -
            routePath: '/save-registration/{event_title}'
            _controller: 'Event::saveRegistration'
            _arguments:
              event_title: event
          -
            routePath: '/save-registration-result/{eventuid}/{reguid}/{result}/{hmac}'
            _controller: 'Event::saveRegistrationResult'
            _arguments:
              eventuid: eventuid
              reguid: reguid
              result: result
              hmac: hmac
          -
            routePath: '/confirm-registration/{reguid}/{hmac}'
            _controller: 'Event::confirmRegistration'
            _arguments:
              reguid: reguid
              hmac: hmac
          -
            routePath: '/cancel-registration/{reguid}/{hmac}'
            _controller: 'Event::cancelRegistration'
            _arguments:
              reguid: reguid
              hmac: hmac
        defaultController: 'Event::registration'
        requirements:
          eventuid: \d+
          reguid: \d+
          result: '[0-8]'
          hmac: '^[a-zA-Z0-9]{40}$'
        aspects:
          eventuid:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_event
            routeFieldName: uid
          reguid:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_registration
            routeFieldName: uid
          event_title:
            type: PersistedAliasMapper
            tableName: tx_sfeventmgt_domain_model_event
            routeFieldName: slug
      EventCalendarPlugin:
        type: Extbase
        limitToPages: [59]
        extension: SfEventMgt
        plugin: Pieventcalendar
        routes:
          -
            routePath: /
            _controller: 'Event::calendar'
          -
            routePath: '/{year}/month/{month}'
            _controller: 'Event::calendar'
            _arguments:
              'month': 'overwriteDemand/month'
              'year': 'overwriteDemand/year'
            requirements:
              month: '\d+'
              year: '\d+'
          -
            routePath: '/{year}/week/{week}'
            _controller: 'Event::calendar'
            _arguments:
              'week': 'overwriteDemand/week'
              'year': 'overwriteDemand/year'
            requirements:
              week: '\d+'
              year: '\d+'
        defaultController: 'Event::calendar'
        aspects:
          month:
            type: StaticRangeMapper
            start: '1'
            end: '12'
          week:
            type: StaticRangeMapper
            start: '1'
            end: '53'
          year:
            type: StaticRangeMapper
            start: '2000'
            end: '2030'

Note, that some requirements are too loose (e.g. eventuid, reguid) and can not be simplified, so therefore
a `cHash` parameter will be added to the route automatically.

The extension also extends sys_category with a slug field the same way as ext:news does. Please note, that
the overwriteDemand/category argument is a string, which can contain *multiple* category UIDs. When you
use the overwriteDemand for categories with only *one* category, the example configuration above will work.
If you pass multipl, comma separated category UIDs to the overwriteDemand/category argument, you have to
implement you own routing aspect.

