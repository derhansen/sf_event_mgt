.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _developer_payment:

=======
Payment
=======

The extension supports custom payment methods, which can be added by creating an own extension that adds a new
payment method and implement Event Listeners for the PSR-14 Events for the different payment actions. It is
required, that the payment is processed by an external payment provider (e.g. Paypal payment page).
Please refer to the General workflow image shown below.

It is also required, that each Event Listener uses a Fluid standalone view to render the output that will be
shown in the desired payment action in sf_event_mgt

.. note::
   Please note, that it is only possible to start the payment process **after** the registration has been
   confirmed by the user.

This section describes how to create your own payment solution for sf_event_mgt which makes use of the provided
payment actions.

I will assume, that the new payment method is called :php:`mypaymentmethod` and the extension key for the new
payment method is :php:`sf_event_mgt_mypaymentmethod`

General workflow
----------------

.. figure:: /Images/payment-workflow.png
   :alt: Payment workflow
   :class: with-shadow

Depending on the selected payment method, the user is redirected to the payment providers payment page.

As a reference, please check this demo extension: https://github.com/derhansen/sf_event_mgt_payment_demo

1. Blank extension
------------------

First of all you need a blank TYPO3 extension with at least an :php:`ext_emconf.php` and an :php:`ext_localconf.php` file.

2. Registration of payment method
---------------------------------

Add the following content to the file :php:`ext_localconf.php`::

 // Register payment provider
 $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods']['mypaymentmethod'] = [
     'class' => 'DERHANSEN\\SfEventMgtMypaymentmethod\\Payment\\Mypaymentmethod',
     'extkey' => 'sf_event_mgt_mypaymentmethod'
 ];

3. Implement Event Listeners
----------------------------

Depending on the requirements of the payment method, you should implement an Event Listener for available PSR-14 Events.
You should at least implement handling of redirect, success, failure and cancel actions.

The code below shows how to implement your payment method to the redirectAction PSR-14 Event of sf_event_mgt::

 // Configuration/Services.yaml
 services:
   Vendor\Extension\EventListener\YourListener:
     tags:
       - name: event.listener
         identifier: 'yourListener'
         event: DERHANSEN\SfEventMgt\Event\ProcessPaymentInitializeEvent

After you registered your event listener, you can add code for your Event Listener to initialize the payment::

 <?php
 namespace Vendor\Extension\EventListener;

 use DERHANSEN\SfEventMgt\Event\ModifyDetailViewVariablesEvent;

 class YourListener
 {
     public function __invoke(ModifyDetailViewVariablesEvent $modifyDetailViewVariablesEvent): void {
        // Implement your code (e.g. add variables)
        $variables = $modifyDetailViewVariablesEvent->getVariables();
        $variables['newVariable'] = 'Just testing';
        $modifyDetailViewVariablesEvent->setVariables($variables);
     }
 }

The setters in all events allow you to control the behavior of the payment process in the main extension.

4. Add payment class
--------------------

Please refer to the class :php:`AbstractPayment` in sf_event_mgt for possible settings. You payment class
must extend :php:`AbstractPayment` and you should override/set the local :php:`$enable` properties in order
to enable the actions in sf_event_mgt

Please also refer to the :php:`PaymentController` in sf_event_mgt to see all available PSR-14 Events.

In this example I create the class :php:`DERHANSEN\SfEventMgtMypaymentmethod\Payment\Mypaymentmethod` and add
the following method.::

 /**
  * Adds required HTML (for redirection) to the given values-array
  *
  * @param array $values
  * @param bool $updateRegistration
  * @param \DERHANSEN\SfEventMgt\Domain\Model\Registration $registration
  * @param ActionController $pObj
  * @return void
  */
 public function renderRedirectView(&$values, &$updateRegistration, $registration, $pObj)
 {
     $pluginSettings = $this->getPluginSettings();

     $view = GeneralUtility::makeInstance(StandaloneView::class);
     $view->setFormat('html');
     $view->setLayoutRootPaths($pluginSettings['view']['layoutRootPaths']);
     $view->setPartialRootPaths($pluginSettings['view']['partialRootPaths']);
     $view->setTemplateRootPaths($pluginSettings['view']['templateRootPaths']);

     // @todo - e.g. call payment providers API to initialize payment
     //
     // Make sure to multiply the price with the given amount of depending registrations
     //
     // Depending on the payment provider you may receive a redirection URL or a token
     // which can be passed to the standalone view.

     $view->assignMultiple([
         'settings' => $pluginSettings['settings'],
     ]);
     $values['html'] = $view->render();
 }

Replace the @todo section with your own code. The returned view should include some text
and at least the link for the redirection. In order process automatic redirection, the
view could include a JavaScript redirect to the payment providers payment page.

5. Implement methods
--------------------

Step 4 already showed how to implement one action. Feel free to implement other required
actions (at least :php:`success`, :php:`failure` and :php:`cancel` to your need.

Each PSR-14 Event enables you to update the given registration. Just set the properties of the
:php:`$registration` object and set :php:`$updateRegistration` to :php:`true`.

It is also possible to remove a registrations, if payment failed or was cancelled. Please
see the corresponding PSr-14 Events for possible options.

6. cHash in generated links
---------------------------

All links created in :php:`PaymentController` will automatically have a cHash added by TYPO3.
This should be ok for most scenarios, but sometimes the payment provider will append GET
parameters to links (e.g. successUrl or failureUrl), which then leads to the situation,
that the TYPO3 cHash check fails.

Since all Payment actions are uncached and the registration GET parameter is checked
using a HMAC, the cHash can manually be removed from generated URLs by implementing
the :php:`ProcessPaymentInitializeEvent` PSR-14 event.


7. Security conciderations
--------------------------

Make sure that your rendered Fluid standlone views do not contain sensitive data or possibilities
for Cross Site Scripting (XSS) (:php:`values['html']` is rendered with :php:`f:render.raw`).
