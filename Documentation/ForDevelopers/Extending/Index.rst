.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _extending:

Extending
=========

If you want to extend `sf_event_mgt`, I recommend to use the extension `extender` (https://typo3.org/extensions/repository/view/extender)
from Sebastian Fischer. It offers an easy way to extend existing Extbase domain models without running into problems
in PHP7 with methods not having the same signature.

A demo extension with a short step by step manual is available on
GitHub https://github.com/derhansen/sf_event_mgt_extend_demo
