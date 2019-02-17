.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _route_enhancers:

Route Enhancers
===============

Since TYPO3 9.5, the TYPO3 Core comes with speaking URLs out of the box. For Extbase Extensions, the administrator
can configure route enhancers to create speaking URLs for extension parameters.

The following example shows a basic configuration for routes of sf_event_mgt

Configuration::

    routeEnhancers:
      EventPlugin:
        type: Extbase
        limitToPages: [12,13,14,15,16]
        extension: SfEventMgt
        plugin: Pievent
        routes:
          - { routePath: '/', _controller: 'Event::list' }
          - { routePath: '/{event_title}', _controller: 'Event::detail', _arguments: {'event_title': 'event'} }
          - { routePath: '/{event_title}/ical', _controller: 'Event::icalDownload', _arguments: {'event_title': 'event'} }
          - { routePath: '/{event_title}', _controller: 'Event::registration', _arguments: {'event_title': 'event'} }
          - { routePath: '/save-registration/{event_title}', _controller: 'Event::saveRegistration', _arguments: {'event_title': 'event'} }
          - { routePath: '/save-registration-result/{eventuid}/{save_registration_result}/{hmac}', _controller: 'Event::saveRegistrationResult', _arguments: {'eventuid': 'eventuid', 'result': 'result', 'hmac': 'hmac'} }
          - { routePath: '/confirm-registration/{reguid}/{hmac}', _controller: 'Event::confirmRegistration', _arguments: {'reguid': 'reguid', 'hmac': 'hmac'} }
          - { routePath: '/cancel-registration/{reguid}/{hmac}', _controller: 'Event::cancelRegistration', _arguments: {'reguid': 'reguid', 'hmac': 'hmac'} }
          - { routePath: '/location/{location_title}', _controller: 'Event::list', _arguments: {'location_title': 'overwriteDemand/location'}}
          - { routePath: '/speaker/{speaker_name}', _controller: 'Event::list', _arguments: {'speaker_name': 'overwriteDemand/speaker'}}
          - { routePath: '/organisator/{speaker_name}', _controller: 'Event::list', _arguments: {'organisator_name': 'overwriteDemand/organisator'}}
        defaultController: 'Event::list'
        requirements:
          eventuid: '[0-9]{1..3}'
          reguid: '[0-9]{1..3}'
          result: '[0-8]'
          hmac: '^[a-zA-Z0-9]{40}$'
        aspects:
          event_title:
            type: PersistedAliasMapper
            tableName: 'tx_sfeventmgt_domain_model_event'
            routeFieldName: 'slug'
          location_title:
            type: PersistedAliasMapper
            tableName: 'tx_sfeventmgt_domain_model_location'
            routeFieldName: 'slug'
          speaker_name:
            type: PersistedAliasMapper
            tableName: 'tx_sfeventmgt_domain_model_speaker'
            routeFieldName: 'slug'
          organisator_name:
            type: PersistedAliasMapper
            tableName: 'tx_sfeventmgt_domain_model_organisator'
            routeFieldName: 'slug'

Note, that some requirements are too loose (e.g. eventuid, reguid) and can not be simplified, so therefore
a `cHash` parameter will be added to the route automatically.

Also note, that sf_event_mgt does not extend sys_category with a slug field, since ext:news already does so. So if you
for example want to use the title of a sys_category record as overwriteDemand route parameter and do not use ext:news,
you may have to extend sys_category with a slug field yourself.

