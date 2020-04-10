# Codeception test execution

Currently, tests are executed manually using a prepared TYPO3 website. Should be fully automated at some time.  

1. Install composer dependencies

   `composer require nimut/typo3-complete="~10.3"`

2. Start Selenium:

   `java -Djava.awt.headless=true -jar ~/Selenium/selenium-server-standalone-3.141.59.jar`
   
3. Execute Acceptance Testsuite

   `.Build/bin/codecept run acceptance --env local -c Tests/Build/AcceptanceTests.yml`