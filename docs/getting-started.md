#Start Selenium

Open a terminal window and issue the appropriate command below depending on which browser you wish to use

##Firefox
Firefox __before__ version 46.0 is needed due to a bug in later versions

`java -jar "%HOMEPATH%\Selenium\selenium-server-standalone-2.53.0.jar"`

Firefox version 47 doesn't work at all

Firefox __after__ version 48.0 works just fine, but with the new marionette driver (https://developer.mozilla.org/en-US/docs/Mozilla/QA/Marionette), we need to tell Selenium where it can be found on our PC

`java -jar -Dwebdriver.gecko.driver="%HOMEPATH%\Developers Share\Selenium\geckodriver-v0.10.0-win64\geckodriver.exe" "%HOMEPATH%\Developers Share\Selenium\selenium-server-standalone-3.0.0-beta4.jar"`


##Chrome (also works for Firefox)

`java -jar "%HOMEPATH%\Developers Share\Selenium\selenium-server-standalone-2.53.0.jar" -port 4444 -Dwebdriver.chrome.driver="%HOMEPATH%\Developers Share\chromedriver\chromedriver_win32\chromedriver.exe"`

##InternetExplorer (works for Chrome and Firefox aswell)

`java -jar "%HOMEPATH%\Developers Share\Selenium\selenium-server-standalone-2.53.0.jar" -port 4444 -Dwebdriver.chrome.driver="%HOMEPATH%\Developers Share\chromedriver\chromedriver_win32\chromedriver.exe -Dwebdriver.ie.driver=C:\Users\anil.bheema\Developers Share\Selenium\IEDriverServer_Win32_2.48.0\IEDriverServer.exe"`

#Run a Behat test

Choose the tag most appropriate to what you wish to test

Open a terminal window and issue the command

`vendor\bin\behat test\Features\ASDA\AsdaSmokeTestPack.feature --tags=asda-2`








