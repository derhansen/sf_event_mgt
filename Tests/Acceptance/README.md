# Local test execution

1. Start Selenium:

   `java -Djava.awt.headless=true -jar ~/Selenium/selenium-server-4.9.0.jar standalone`

2. Execute Acceptance Testsuite

   `.Build/vendor/bin/codecept run acceptance --env local -c .Build/codeception/AcceptanceTests.yml`
