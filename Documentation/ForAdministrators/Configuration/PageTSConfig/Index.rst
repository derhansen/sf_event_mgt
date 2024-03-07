.. include:: /Includes.rst.txt

.. page-tsconfig-settings:

=============
Page TSConfig
=============

The following Page TSConfig can be used with the extension.

templateLayout
--------------

.. confval:: templateLayout

   :type: array

   With this setting, the plugin can be configured to show different template layouts.
   Template layouts can be configured as shown below::

       tx_sfeventmgt.templateLayouts {
         1 = 2 column layout
         2 = Event-Slider
       }

   Template layout can be used/set by TypoScript (settings.templateLayout)


module.defaultPid.tx_sfeventmgt_domain_model_event
--------------------------------------------------

.. confval:: module.defaultPid.tx_sfeventmgt_domain_model_event

   :type: int

   This setting allows setting the default storage pid of new events generated over the backend module.
   To set the default storage pid for new event records, for example, to a sysfolder with the pid 20, use::

       tx_sfeventmgt.module.defaultPid.tx_sfeventmgt_domain_model_event = 20
