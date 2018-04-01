.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _signals:

Signal slots
============

Signals
-------
Signals are currently only used in the actions of the payment controller to control the behavior of the output
and the processing of the payment of a registration.

.. t3-field-list-table::
 :header-rows: 1

 - :Controller:
         Controller:

   :Action:
         Action:

   :Signal:
         Signal:

   :Description:
         Description:

 - :Controller:
         Event

   :Action:
         list

   :Signal:
         'listActionBeforeRenderView'

   :Description:
         Signal is called before rendering the list view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         calendar

   :Signal:
         'calendarActionBeforeRenderView'

   :Description:
         Signal is called before rendering the calendar view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         detail

   :Signal:
         'detailActionBeforeRenderView'

   :Description:
         Signal is called before rendering the detail view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         registration

   :Signal:
         'registrationActionBeforeRenderView'

   :Description:
         Signal is called before rendering the registration view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         saveRegistration

   :Signal:
         'saveRegistrationActionAfterRegistrationSaved'

   :Description:
         Signal is called after a registration is saved. The registration is passed to the signal.

 - :Controller:
         Event

   :Action:
         confirmRegistration

   :Signal:
         'confirmRegistrationActionBeforeRenderView'

   :Description:
         Signal is called before rendering the confirmRegistration view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         cancelRegistration

   :Signal:
         'cancelRegistrationActionBeforeRenderView'

   :Description:
         Signal is called before rendering the cancelRegistration view. An array with all view values is passed by reference.

 - :Controller:
         Event

   :Action:
         search

   :Signal:
         'searchActionBeforeRenderView'

   :Description:
         Signal is called before rendering the search view. An array with all view values is passed by reference.

 - :Controller:
         Payment

   :Action:
         redirect

   :Signal:
         'redirectActionBeforeRedirect' + ucfirst($paymentMethod)

   :Description:
         Signal is called before rendering the redirect view. Use this signal to create the views HTML content,
         that redirects the user to the payment providers payment page.

 - :Controller:
         Payment

   :Action:
         success

   :Signal:
         'successActionProcessSuccess' + ucfirst($paymentMethod)

   :Description:
         Signal is called before rendering the success view. Use this signal to create the views HTML content
         and also use this signal to modify the payment status of the registration after a successful payment.

 - :Controller:
         Payment

   :Action:
         failure

   :Signal:
         'failureActionProcessFailure' + ucfirst($paymentMethod)

   :Description:
         Signal is called before rendering the failure view. Use this signal to create the views HTML content
         and also use this signal to modify/delete the registration after a failed payment.

 - :Controller:
         Payment

   :Action:
         failure

   :Signal:
         'cancelActionProcessCancel' + ucfirst($paymentMethod)

   :Description:
         Signal is called before rendering the cancel view. Use this signal to create the views HTML content
         and also use this signal to modify/delete the registration after a cancelled payment.

 - :Controller:
         Payment

   :Action:
         notify

   :Signal:
         'notifyActionProcessNotify' + ucfirst($paymentMethod)

   :Description:
         Signal is called before rendering the notify view. Use this signal to create the views HTML content
         and also use this signal to modify the registration when the payment provider supports a server to server
         nofitication URL.


