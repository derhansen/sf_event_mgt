.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _custom-notifications:

Custom notifications
====================

If you use the registration option for events, you have to possibility to send
custom notifications to all (confirmed) participants of the event. In order to
do so, use the admin module to open the "Notify participants" view.

.. figure:: ../../Images/event-custom-notification.png
   :align: left
   :alt: Actions of the backend module

Create an own notification
~~~~~~~~~~~~~~~~~~~~~~~~~~

To create an own notification, you first need to create a HTML template that will
be used as the notification body. The template must be located in the following
path::

  Templates/Notification/User/Custom/

In this example, I create the file **MyNotification.html**

You can use the following objects in your template:

* {registration}
* {event}
* {settings}
* {hmac}
* {reghmac}

After you created the notification template, you have to configure the new notification
in the TypoScript settings of the **admin module**.::

  module.tx_sfeventmgt {
    settings {
      notification {
        customNotifications {
          myNotification {
            title = A title for the notification
            template = MyNotification.html
            subject = A subject for the e-mail
          }
        }
      }
    }
  }

After configuring the new notification to the TypoScript settings, you can use it to
notifiy participants of the event.