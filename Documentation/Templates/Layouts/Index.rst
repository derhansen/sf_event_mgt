.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _template_layouts:

Using template layouts for listview
===================================

Different template layouts can be configured in Page TSConfig.::

  tx_sfeventmgt.templateLayouts {
    1 = 2 column layout
    2 = Event-Slider
  }

This TypoScript above defines 2 new template layouts, which will appear in the template
layout selectbox in the plugin settings like shown below.

.. figure:: ../../Images/plugin-template-layout.png
   :align: left
   :width: 500px
   :alt: Different template layouts

The choosen template layout can be used in the fluid templates as shown below.::

  <f:if condition="{settings.templateLayout} == 1">
    <f:render partial="List/Event-Slider" arguments="{eventItem: event, settings:settings}"/>
  </f:if>

