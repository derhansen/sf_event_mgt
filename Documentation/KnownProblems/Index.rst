.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _known-problems:

Known Problems
==============

* It is not possible to translate e-mails sent by the extension
* iCal download does not work, if ``$TYPO3_CONF_VARS['FE']['compressionLevel']`` is set to a value > 0 - https://forge.typo3.org/issues/69223
* Templates for all notifications sent by the extension must be configured by using ``plugin.tx_sfeventmgt.view.templateRootPath`` (sigular!), since overriding single template for a standalone view is not supported by TYPO3 6.2 (support has been added in TYPO3 7.3)

Other known problems are listed on GitHub at https://github.com/derhansen/sf_event_mgt

If you find a bug or have a feature request for this extension,
please use the issuetracker on GitHub at https://github.com/derhansen/sf_event_mgt