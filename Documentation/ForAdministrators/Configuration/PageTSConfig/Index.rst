.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. page-tsconfig-settings:

Page TSConfig
=============

The following Page TSConfig can be used with the extension.

.. t3-field-list-table::
 :header-rows: 1

 - :Property:
         Property:

   :Data type:
         Data type:

   :Description:
         Description:

 - :Property:
         templateLayout

   :Data type:
         array

   :Description:
         With this setting the plugin can be configured to show different template layouts.
         Template layouts can be configured like shown below.::

           tx_sfeventmgt.templateLayouts {
             1 = 2 column layout
             2 = Event-Slider
           }

         Template layout can be used/set by TypoScript (settings.templateLayout)

 - :Property:
         module.defaultPid.tx_sfeventmgt_domain_model_event

   :Data type:
         int

   :Description:
         This setting allows to set the default storage pid of new events generated over the backend module.
         To set the default storage pid for new event records to e.g. a sysfolder with the pid 20 use::

           tx_sfeventmgt.module.defaultPid.tx_sfeventmgt_domain_model_event = 20

