.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _priceoption:

============
Price Option
============

Price options can be used to define multiple prices of which the user must
choose one during the registration process. The selected price option is
saved to the user registration and the price on registration is saved to
a dedicated field.

If an event has price options defined and registration is enabled, the user
must chose one of the available price options in the registration process.

Price options can be limited to one or multiple frontend user groups and
can be used with the TYPO3 start/stop fields in order to show/hide a
price option on a specific date.

When a registration is saved, the price option and the price option price
is saved to a dedicated field of the registration record.

.. figure:: /Images/event-priceoptions.png
   :alt: Price options
   :class: with-shadow

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
         Field:

   :Description:
         Description:

 - :Field:
         Title

   :Description:
         Title of the price option.

 - :Field:
         Description

   :Description:
         Description of the price option.

 - :Field:
         Price

   :Description:
         The price of the price option

 - :Field:
         Date until the price is valid (includes selected date)

   :Description:
         A date, until the price option is considered as valid and selectable
         in the registration process.

