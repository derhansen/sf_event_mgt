.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _spamcheck:

Configurable and extendable spam check
======================================

It is possible to configure spam checks which are executed on form submission. A configurable max spam score is used
to determine, if a form submission is considered as spam or not. Each spam check can increase the total spam score
by a configurable amount.

Configuration
~~~~~~~~~~~~~

The configuration is (hopefully) self explaining.

The spam check must be activated using Typoscript.::

  plugin.tx_sfeventmgt.settings.registration {
    spamCheck {
      enabled = 0
      maxSpamScore = 10
      checks {
        honeypot {
          enabled = 0
          name = Honeypot field check
          class = DERHANSEN\SfEventMgt\SpamChecks\HoneypotSpamCheck
          increaseScore = 10
            configuration {
              renderAsHiddenField = 0
            }
          }
      }
    }
  }

To enable the spam check, :php:`plugin.tx_sfeventmgt.settings.registration.spamCheck.enabled = 1` must be set.
Next, each spam check must be activated and configured to your needs.

The honeypot spam check
~~~~~~~~~~~~~~~~~~~~~~~

This check adds a field (either invisible input field or a hidden form field) to the registration form.
If the field is filled out, it is very likely that the form submission is spam. Therefore you should configure
a high :php:`increaseScore`, so the spam check as a whole already is considered as failed when this check fails.

Example template::

  <f:if condition="{spamSettings.checks.honeypot.configuration.renderAsHiddenField} == 1">
    <f:then>
        <f:form.hidden property="hp{event.uid}" additionalAttributes="{autocomplete: 'hp{event.uid}'}" />
    </f:then>
    <f:else>
        <f:form.textfield property="hp{event.uid}" additionalAttributes="{autocomplete: 'hp{event.uid}', aria-hidden: 'true'}" tabindex="-1" style="display:none !important" />
    </f:else>
  </f:if>

If this spam check is configured and the field is not submitted (missing in POST parameters), the check is also
considered as failed.

The link spam check
~~~~~~~~~~~~~~~~~~~

This check counts the amount of links in all submitted form fields. If the configured :php:`maxAmountOfLinks` is
exceeded, the check is considered failed.

The challenge/response spam check
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This check adds a hidden input field to the registration form. The check expect a specific value to be submitted
in the hidden input field. If this value is not present, the check is considered failed.

The spam check has the following configuration options::

  challengeResponse {
    enabled = 0
    name = Challenge/Response check (JavaScript required) using ROT13 encryption/obfuscation
    class = DERHANSEN\SfEventMgt\SpamChecks\ChallengeResponseSpamCheck
    increaseScore = 10
    configuration {
      prefix = SfEventMgt
      postfix = TYPO3
    }
  }

The spam check calculates a challenge consisting of the configured pre- and postfix and a hmac which includes the
uid of the event. This challenge is added as data-attribute to the hidden form field.

The check expects the challenge to be returned ROT13 encrypted/encoded. There is a plain vanilla JS script in
:php:`Resources/Public/JavaScript/cr-spamcheck.js` that does the job for you, if you use the included partial for
the spam checks of the extension.

Creating a custom spam check
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

It is also possible to create custom spam checks. To do so, just add an own configuration array to the
:php:`checks` array and implement your check as a class that extends :php:`DERHANSEN\SfEventMgt\SpamChecks\AbstractSpamCheck`

Please refer to the existing spam checks in the extension for details.
