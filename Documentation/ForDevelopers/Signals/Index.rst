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

 - :Class:
         Class:

   :Name:
         Name:

   :Arguments:
         Arguments:

   :Description:
         Description:

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         listActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the list view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         calendarActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the calendar view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         detailActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the detail view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         registrationActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the registration view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         saveRegistrationActionAfterRegistrationSaved

   :Arguments:
         $registration, $this

   :Description:
         Signal is called after a registration is saved. The registration is passed to the signal.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         saveRegistrationActionBeforeCreateDependingRegistrations

   :Arguments:
         $registration, &$createDependingRegistrations, $this

   :Description:
         Signal is called before depending registrations are created. $createDependingRegistrations is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         confirmRegistrationActionAfterRegistrationConfirmed

   :Arguments:
         $registration, $this

   :Description:
         Signal is called after a registration has been confirmed. The registration is passed to the signal.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         confirmRegistrationActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the confirmRegistration view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         cancelRegistrationActionWaitlistMoveUp

   :Arguments:
         $event, $this

   :Description:
         Signal is after a registration is cancelled. Use this signal to move registrations from the waitlist up to
         the confirmed registrations.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         cancelRegistrationActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the cancelRegistration view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Event

   :Name:
         searchActionBeforeRenderView

   :Arguments:
         &$values, $this

   :Description:
         Signal is called before rendering the search view. An array with all view values is passed by reference.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Payment

   :Name:
         'redirectActionBeforeRedirect' + ucfirst($paymentMethod)

   :Arguments:
         &$values, &$updateRegistration, $registration, $this

   :Description:
         Signal is called before rendering the redirect view. Use this signal to create the views HTML content,
         that redirects the user to the payment providers payment page.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Payment

   :Name:
         'successActionProcessSuccess' + ucfirst($paymentMethod)

   :Arguments:
         &$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this

   :Description:
         Signal is called before rendering the success view. Use this signal to create the views HTML content
         and also use this signal to modify the payment status of the registration after a successful payment.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Payment

   :Name:
         'failureActionProcessFailure' + ucfirst($paymentMethod)

   :Arguments:
         &$values, &$updateRegistration, &$removeRegistration, $registration, GeneralUtility::_GET(), $this

   :Description:
         Signal is called before rendering the failure view. Use this signal to create the views HTML content
         and also use this signal to modify/delete the registration after a failed payment.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Payment

   :Name:
         'cancelActionProcessCancel' + ucfirst($paymentMethod)

   :Arguments:
         &$values, &$updateRegistration, &$removeRegistration, $registration, GeneralUtility::_GET(), $this

   :Description:
         Signal is called before rendering the cancel view. Use this signal to create the views HTML content
         and also use this signal to modify/delete the registration after a cancelled payment.

 - :Class:
         DERHANSEN\\SfEventMgt\\Controller\\Payment

   :Name:
         'notifyActionProcessNotify' + ucfirst($paymentMethod)

   :Arguments:
         &$values, &$updateRegistration, $registration, GeneralUtility::_GET(), $this

   :Description:
         Signal is called before rendering the notify view. Use this signal to create the views HTML content
         and also use this signal to modify the registration when the payment provider supports a server to server
         nofitication URL.

 - :Class:
         DERHANSEN\\SfEventMgt\\Domain\\Model\\Repository\\EventRepository

   :Name:
         findDemandedModifyQueryConstraints

   :Arguments:
         &$constraints, $query, $eventDemand, $this

   :Description:
         Signal is called after all query constraints are collected. The signal enables the possibility to add/modify
         the query constraints for the findDemanded function. Very usefull, when you extend the eventDemand with custom
         properties.

 - :Class:
         DERHANSEN\\SfEventMgt\\Service\\NotificationService

   :Name:
         sendAdminMessageAfterNotificationSent

   :Arguments:
         $registration, $body, $subject, $attachments, $senderName, $senderEmail, $this

   :Description:
         Signal is called after all admin messages have been sent. Use this signal, if you want to send additional
         admin notifications (e.g. to speakers). Note, that the signal only gets called, when either "Notify Admin" or
         "Notify Organisator" is checked.

 - :Class:
         DERHANSEN\\SfEventMgt\\Service\\NotificationService

   :Name:
         sendUserMessageAfterNotificationSent

   :Arguments:
         $registration, $body, $subject, $attachments, $senderName, $senderEmail, $replyToEmail, $this

   :Description:
         Signal is called after a user message has been sent. Use this signal, if you want to send additional
         notifications (e.g. BCC of the user email to an additional e-mail address).

 - :Class:
         DERHANSEN\\SfEventMgt\\Service\\NotificationService

   :Name:
         sendUserMessageCustomSenderData

   :Arguments:
         &$senderName, &$senderEmail, &$replyToEmail, $registration, $type, $this

   :Description:
         Signal is called before a user message will be sent. Use this signal, if you want to modify the sender
         data (e-mail address, name and reply-to) to a custom value (e.g. set sender e-mail address to e-mail address
         of organisator)

 - :Class:
         DERHANSEN\\SfEventMgt\\Service\\NotificationService

   :Name:
         sendUserMessageCustomAttachmentData

   :Arguments:
         &$attachments, $registration, $type, $this

   :Description:
         Signal is called before a user message will be sent. Use this signal, if you want to add custom attachments
         that can not be added using the TypoScript attachment settings (see :ref:`email-attachments`).

         As an example, you can use this signal to create a PDF invoice, which you add to the $attachments variable
         in case a user registered for an event and selected "Invoice" as payment method.

