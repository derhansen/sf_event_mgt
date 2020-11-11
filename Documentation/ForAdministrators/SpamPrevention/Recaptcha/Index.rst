.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _recaptcha:

Configurable reCAPTCHA field
============================

It is possible to add a reCAPTCHA v2 field in the registration form. If configured, it is only possible to
submit the form when the captcha is "solved". reCAPTCHA sometimes just works by checking a checkbox, but
it does also often ask user to identify objects of a larger image.

Configuration
-------------

reCAPTCHA does only work with valid Google API credentials. To obtain API credentials, you need a Google Account.
Once you registered for the reCAPTCHA service, you will get 2 keys (site key and secret key) which must be added
to the TypoScript settings (constants) of sf_event_mgt.
To activate the validation for reCAPTCHA, you will also have to add 'recaptcha' to the list of required fields.

TypoScript Constants::

  plugin.tx_sfeventmgt {
    settings {
      reCaptcha {
        siteKey = <your-site-key>
        secretKey = <your-secret-key>
      }
      registration.requiredFields = recaptcha
    }
  }

.. caution::
   You should evaluate, if the usage of a reCAPTCHA field is inline with local laws (e.g. privacy concerns due to GDPR)

