# Local test execution

1. Start Selenium:

   `java -Djava.awt.headless=true -jar ~/Selenium/selenium-server-standalone-3.141.59.jar`
   
2. Execute Acceptance Testsuite

   `.Build/bin/codecept run acceptance --env local -c Tests/Build/AcceptanceTests.yml`