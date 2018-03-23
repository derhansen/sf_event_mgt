How to contribute
=================

Hey, great you want to contribute to ``sf_event_mgt`. 

Submitting feedback
===================
Please report feedback, bugs and feature requests on [GitHub](https://github.com/derhansen/sf_event_mgt/issues)

Note, that the GitHub issue tracker is not a support forum. If you need help on configuring the extension,
either use the [sf_event_mgt Slack Channel](https://typo3.slack.com/messages/C83T6DEKY/) or post your question
on [Stackoverflow](https://stackoverflow.com/questions/tagged/typo3)

I'm always willing to help user of ``sf_event_mgt`` with potential problems, but please understand, that I will
not fix templates, code or misconfigured TYPO3 websites in commercial projects for free. If you need
commercial support, please contact me by email.

Submitting new features
=======================
Not every feature is relevant for the bulk of ``sf_event_mgt`` users, so please discuss new features in the 
issue tracker on [GitHub](https://github.com/derhansen/sf_event_mgt/issues) before starting to code.  

Submitting changes
==================
* Create a fork of the sf_event_mgt repository on GitHub
* Create a new branch from the current master branch
* Make your changes
* Make sure your code complies with the coding standard 
* Make sure all unit- and functional tests are working (will also automatically be checked by Travis CI)
* Add new unit- and/or functional tests for your new code
* Extend the existing documentation if required
* Commit your changes and make sure to add a proper commit message
  * Examples for a proper [commit message](https://docs.typo3.org/typo3cms/ContributionWorkflowGuide/Appendix/GeneralTopics/CommitMessage.html)
* Create a Pull Request on GitHub
  * Describe your changes. The better you describe your change and the reasons for it the more likely it is that it will be accepted.

Coding Standards
================
The sf_event_mgt codebase follows [PSR-1](http://www.php-fig.org/psr/psr-1/) and
[PSR-2](http://www.php-fig.org/psr/psr-2/) standards for code formatting. 

Testing
=======
A wide range of the codebase of ``sf_event_mgt`` is covered by unit- and functional tests. If you submit a pull
request without tests, this is ok, but please note, that it may take longer time to merge your pull requests in
this case, since I have to create the tests for your code.  