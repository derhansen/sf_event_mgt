.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _changing_templates:

Changing paths of the template
==============================

Please do never change templates directly in the Ressources folder of the extensions,
since your changes will get overwritten by extension updates.

You may change the paths to the templates, layouts and partials in the constant settings
of the extension like shown below.::

  plugin.tx_sfeventmgt {
    view {
      templateRootPath = fileadmin/templates/events/Templates/
      partialRootPath = fileadmin/templates/events/Partials/
      layoutRootPath = fileadmin/templates/events/Layouts/
    }
  }

If you don't want to change all templates, layouts and partials, you can also use the
new override function for templates, layouts and partials, which is available since TYPO3 6.2

Configure your TypoScript setup like shown below (note the **plural** of the path-name)::

  plugin.tx_sfeventmgt {
    view {
      templateRootPaths {
        0 = {$plugin.tx_sfeventmgt.view.templateRootPath}
        1 = fileadmin/templates/events/Templates/
      }
      partialRootPaths {
        0 = {$plugin.tx_sfeventmgt.view.partialRootPath}
        1 = fileadmin/templates/events/Partials/
      }
      layoutRootPaths {
        0 = {$plugin.tx_sfeventmgt.view.layoutRootPath}
        1 = fileadmin/templates/events/Layouts/
      }
    }
  }

Doing so, you can just **override single files** from the original templates.