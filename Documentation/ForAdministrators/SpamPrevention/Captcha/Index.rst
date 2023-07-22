.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _captcha:

==========================
Configurable captcha field
==========================

The extension support 2 captcha Services:

* Google reCAPTCHA - https://www.google.com/recaptcha/about/
* hCaptcha - https://hcaptcha.com/

.. caution::
   You should evaluate, which captcha service suits best for your needs, since the reCAPTCHA
   service may not be inline with local laws (e.g. privacy concerns due to GDPR)

Configuration
-------------

Both hCaptcha and Google reCAPTCHA require API credentials, so the captcha can be check against an API.
The API credentials must be added as TypoScript constants (see example below).

TypoScript Constants::

  plugin.tx_sfeventmgt {
    settings {
      registration {
        captcha {
          enabled = 0
          type = hCaptcha
          hCaptcha {
            publicKey =
            privateKey =
          }
          reCaptcha {
            siteKey =
            secretKey =
          }
        }
      }
    }
  }

TypoScript Setup::

  plugin.tx_sfeventmgt {
    settings {
      registration {
        requiredFields = captcha
      }
    }
  }

In order to use one of the captcha services, the following must be fullfilled:

* Obtain API keys for either hCaptcha or Google reCAPTCHA service
* Enable the captcha check in TypoScript constant :php:`plugin.tx_sfeventmgt.settings.registration.captcha.enabled = 1`
* Set the type of captcha service you use in TypoScript constant :php:`plugin.tx_sfeventmgt.settings.registration.captcha.type = 1`
* Add the API credentials to TypoScript constants in the associated section for the captcha service
