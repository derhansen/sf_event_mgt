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

* :typoscript:`plugin.tx_sfeventmgt.view.templateRootPath`
* :typoscript:`plugin.tx_sfeventmgt.view.partialRootPath`
* :typoscript:`plugin.tx_sfeventmgt.view.layoutRootPath`

Those values will automatically be added after the default paths configuration of the extension. If you prefer
to configure the path-values using TypoScript setup, please refer to the example below
(note the **plural** of the path-name)::

  plugin.tx_sfeventmgt {
    view {
      templateRootPaths {
        2 = EXT:sitepackage/Resources/Private/Extensions/SfEventMgt/Templates/
      }
      partialRootPaths {
        2 = EXT:sitepackage/Resources/Private/Extensions/SfEventMgt/Partials/
      }
      layoutRootPaths {
        2 = EXT:sitepackage/Resources/Private/Extensions/SfEventMgt/Layouts/
      }
    }
  }

Doing so, you can just **override single files** from the original templates.
