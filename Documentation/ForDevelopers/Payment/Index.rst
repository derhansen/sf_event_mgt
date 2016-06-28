.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../../Includes.txt


.. _developer_payment:

Payment
=======

The extension supports custom payment methods, which can be added by creating an own extension that adds a new
payment method and handles the signal slots emitted for the different payment actions. It is required, that the
payment is processed by an external payment provider (e.g. Paypal payment page). Please refer to the General
workflow image shown below.

It is also required, that each connected signal uses a Fluid standalone view to render the output that will be
shown in the desired payment action in sf_event_mgt

Please note, that it is only possible to start the payment process **after** the registration has been
confirmed by the user.

This section describes how to create your own payment solution for sf_event_mgt which makes use of the provided
payment actions.

I will assume, that the new payment method is called ``mypaymentmethod`` and the extension key for the new
payment method is ``sf_event_mgt_mypaymentmethod``

General workflow
----------------

.. figure:: ../../Images/payment-workflow.png
   :align: left
   :alt: Payment workflow
   :width: 650px

Depending on the selected payment method, the user is redirected to the payment providers payment page.

1. Blank extension
------------------

First of all you need a blank TYPO3 extension with at least an ``ext_emconf.php`` and an ``ext_localconf.php`` file.

2. Registration of payment method
---------------------------------

Add the following content to the file ``ext_localconf.php``::

 // Register payment provider
 $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['sf_event_mgt']['paymentMethods']['mypaymentmethod'] = [
     'class' => 'DERHANSEN\\SfEventMgtMypaymentmethod\\Payment\\Mypaymentmethod',
     'extkey' => 'sf_event_mgt_mypaymentmethod'
 ];

3. Connect to signals
---------------------

Depending on the requirements of the payment method, you should connect to the available signal slots. You should
at least implement handling of redirect, success, failure and cancel actions.

The code below shows how to connect your payment method to the redirectAction signal slot of sf_event_mgt::

 /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
 $signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
 $signalSlotDispatcher->connect(
     'DERHANSEN\\SfEventMgt\\Controller\\PaymentController',
     'redirectActionBeforeRedirectMypaymentmethod',
     'DERHANSEN\\SfEventMgtMypaymentmethod\\Payment\\Mypaymentmethod',
     'renderRedirectView',
     false
 );

Make sure you use the correct name and class (2. and 3. argument) ans also the name of you method (4. argument).
Check the ``PaymentController`` for the other signal slots and make sure to connect to only connect to signal
slots you really need.

4. Add payment class
--------------------

Please refer to the class ``AbstractPayment`` in sf_event_mgt for possible settings. You payment class
must extend ``AbstractPayment`` and you should override/set the local ``$enable`` properties in order
to enable the actions in sf_event_mgt

Please also refer to the ``PaymentController`` in sf_event_mgt to see function signatures for the
signal slots.

In this example I create the class ``DERHANSEN\SfEventMgtMypaymentmethod\Payment\Mypaymentmethod`` and add
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

     /** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
     $view = $this->objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
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
actions (at least ``success``, ``failure`` and ``cancel`` to your need.

Each signal enables you to update the given registration. Just set the properties of the
``$registration`` object and set ``$updateRegistration`` to ``true``.

It is also possible to remove a registrations, if payment failed or was cancelled. Please
see the corresponding signal slots for possible options.

6. Security conciderations
--------------------------

Make sure that your rendered Fluid standlone views do not contain sensitive data or possibilities
for XSS (``values['html']`` is rendered with ``f:render.raw``).
