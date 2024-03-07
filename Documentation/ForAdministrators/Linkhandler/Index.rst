.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: /Includes.rst.txt


.. _linkhandler:

===========
LinkHandler
===========

**LinkHandler** is the synonym for making it possible for editors to create links to custom records.

.. tip::
   This tutorial is also valid for creating links to any other record.

Configuration for the backend
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

PageTsConfig is used to configure the link browser in the backend.

.. code-block:: typoscript

    TCEMAIN.linkHandler {
        tx_event {
            handler = TYPO3\CMS\Recordlist\LinkHandler\RecordLinkHandler
            label = Events
            configuration {
                table = tx_sfeventmgt_domain_model_event
                # Default storage pid
                storagePid = 26
                # Hide the page tree by setting it to 1
                hidePageTree = 0
            }

            scanAfter = page
        }
    }

Configuration for the frontend
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

The links are now stored in the database with the syntax `<a href="t3://record?identifier=tx_event&uid=123">A link</a>`.
By using TypoScript, the link is transformed into an actual link.


.. code-block:: typoscript

    config.recordLinks {
        tx_event {
            typolink {
                parameter = 29
                additionalParams.data = field:uid
                additionalParams.wrap = &tx_sfeventmgt_pieventdetail[action]=detail&tx_sfeventmgt_pieventdetail[controller]=Event&tx_sfeventmgt_pieventdetail[event]=|
            }
        }
    }
