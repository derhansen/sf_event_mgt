.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _languagemenu:

===================================
Language menu on event detail pages
===================================

If a language menu is rendered on a detail page and the languages are configured to use a strict mode, the following snippet
helps you to setup a proper menu. If no translation exists, the property `available` is set to `false` - just as if the current
page is not translated.

.. code-block:: typoscript

   10 = TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor
   10 {
      as = languageMenu
      addQueryString = 1
   }

   11 = DERHANSEN\SfEventMgt\DataProcessing\DisableLanguageMenuProcessor
   # comma separated list of language menu names
   11.menus = languageMenu
