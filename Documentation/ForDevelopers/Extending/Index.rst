.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _extending:

=========
Extending
=========

A demo extension with a short step by step manual is available on
GitHub https://github.com/derhansen/sf_event_mgt_extend_demo

Extending FlexForm setting "settings.orderField"
================================================

If you need to add a new option to the FlexForm setting "settings.orderField", please read this
blogpost `How to extend existing FlexForm select options of a TYPO3 plugin using Page TSconfig <https://www.derhansen.de/2020/11/typo3-extend-existing-flexform-select-options.html>`__
which shows this task as an example.

.. _allowed-viewhelpers:

Allowing additional ViewHelpers in Fluid string parsing
=======================================================

Some settings of the extension are strings, which are parsed with Fluid before they are used. This
currently applies to the email subjects of registration notifications and custom notifications, which
are configured in TypoScript or in the plugin settings.

Since those strings can be edited by editors with access to the plugin settings, the extension only
allows a restricted set of ViewHelpers to be used when such a string is parsed. By default, the
following ViewHelpers are allowed:

* :php:`TYPO3\CMS\Fluid\ViewHelpers\Format\DateViewHelper` (:html:`f:format.date`)
* :php:`TYPO3\CMS\Fluid\ViewHelpers\TranslateViewHelper` (:html:`f:translate`)

Any other ViewHelper is not executed and is rendered as an empty string. Additionally, a warning is
logged to the TYPO3 log, which contains the class name of the blocked ViewHelper.

.. note::
   This restriction only applies to strings parsed with Fluid. Fluid templates of the extension
   (e.g. notification templates) are not affected and can use any ViewHelper.

If you need additional ViewHelpers in such strings, you can register them by adding their class names
to the following array in the file :php:`ext_localconf.php` of your own extension::

 // Allow additional ViewHelpers in Fluid string parsing
 $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['parseStringFluid']['additionalAllowedViewHelpers'][]
     = \TYPO3\CMS\Fluid\ViewHelpers\Format\CaseViewHelper::class;

.. warning::
   Only allow ViewHelpers, which you consider safe in the context described above. ViewHelpers, which
   for example render arbitrary files, execute TypoScript or output raw content must not be added to
   the list of allowed ViewHelpers.
