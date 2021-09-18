.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt

.. _filtering_listview:

Filtering the listview
======================

In the :ref:`eventplugin-settings` you can define criterias for the listview, so it e.g. only
shows the events of a special category or for a special location. Nearly all settings
affecting the demand of the shown events can be overwritten by a URL parameter called
:php:`overwriteDemand`.

Below follows some examples for the overwriteDemand setting:

Filter by category
~~~~~~~~~~~~~~~~~~

The following code snippet shows a list of links which restrict the category to be shown
to the given category::

    <f:for each="{categories}" as="category">
        <f:if condition="{overwriteDemand.category} == {category.uid}">
            <f:then>
                <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{category: category}}" class="active">{category.title}</f:link.action>
            </f:then>
            <f:else>
                <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{category: category}}">{category.title}</f:link.action>
            </f:else>
        </f:if>
    </f:for>

As all links are generated using the :php:`f:link.action` viewHelper, they are fully cached.

Filter by special location properties
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

It is possible to filter events by the location city and country. Using the overwriteDemand
parameter is as following::

    <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{locationCity: 'Hamburg'}}">Show all events in Hamburg</f:link.action>

    <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{locationCountry: 'Germany'}}">Show all events in Germany</f:link.action>

Filter by year, month and/or day
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

It is possible to filter events by a specific year, month or day. Using the overwriteDemand
parameter is as following::

    <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{year: '2017'}}">All events in 2017</f:link.action>

    <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{year: '2017', month: '10'}}">All events in october of the year 2017</f:link.action>

    <f:link.action action="list" controller="Event" arguments="{overwriteDemand:{year: '2017', month: '10', day: '1'}}">All events on the 1st of october 2017</f:link.action>

The filtering also respects events, which are in between the given filter criteria. If you for example set
the filter option to filter events for the 2st of october 2017, then also an event will be shown, that start
at the 1st of october and ends at the 3rd of october.
