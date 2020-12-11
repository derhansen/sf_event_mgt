.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../../Includes.txt


.. _changing_templates:

Changing paths of the template
==============================

Please do never change templates directly in the Ressources folder of the extensions,
since your changes will get overwritten by extension updates.

The easiest way to override templates is to set the following constants:

* plugin.tx_sfeventmgt.view.templateRootPath
* plugin.tx_sfeventmgt.view.partialRootPath
* plugin.tx_sfeventmgt.view.layoutRootPath

Those values will automatically be added after the default paths configuration of the extension. If you prefer
to configure the path-values using TypoScript setup, please refer to the example below
(note the **plural** of the path-name)::

  plugin.tx_sfeventmgt {
    view {
      templateRootPaths {
        2 = fileadmin/templates/events/Templates/
      }
      partialRootPaths {
        2 = fileadmin/templates/events/Partials/
      }
      layoutRootPaths {
        2 = fileadmin/templates/events/Layouts/
      }
    }
  }

Doing so, you can just **override single files** from the original templates.
