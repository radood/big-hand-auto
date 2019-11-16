<?php

namespace Components;

/**
*
 */

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\MinkExtension\Context\RawMinkContext;
use Config\Config;
use Objects\Common;
use Objects\CustomerCareObjects;
use Objects\HomeObjects;
use WebDriver\Exception;
use WebDriver\Key;


class SeleniumHelper extends RawMinkContext implements Context
{
    /**
     * Helper constructor.
     * Initiates test configurations and requires object repositories.
     */

    /**
     * use of Traits
     * TODO these trains are mis-used here, need to make them real classes and use namespaces
     * some can be left as traits but the majority should not be
     */

    use HomeObjects;
    use Common;
    use CustomerCareObjects;
    use Config;

    protected $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected $integers = '0123456789';
    protected $specialCharacters = '0stuvwxyz!@#$%&*?£()ABCDEFGHIJKLMNOPQRSTUVWXYZ';


    /**
     * clicks locator web object.
     *
     * @param $locator
     *
     * @throws \RuntimeException
     */
    public function click($locator)
    {
        $this->waitForElementVisible($locator);

        $NUM_OF_ATTEMPTS = 80;
        $attempts = 0;

        do  {
            try {
                $this->getElement($locator)->click();

            } catch (\Exception $e) {
                $attempts++;
                $this->wait(0.25);
                continue;
            }
            break;
        } while($attempts < $NUM_OF_ATTEMPTS);
    }

    public function enterDate($element, $date, $randon = null)
    {
        $date = $this->getDate($date);
        $this->type($element, $date);
        if ($randon !== null) {
            $this->click($randon);
        }
        return $date;
    }

    public function getDate($date)
    {
        if ('AFTERONEMONTH' === strtoupper($date)) {
            $date = date('Y-m-d H:i', strtotime('+1 month'));
        }
        if ('AFTERONEDAY' === strtoupper($date)) {
            $date = date('Y-m-d H:i', strtotime('+1 day'));
        }
        if ('CURRENTDATE' === strtoupper($date)) {
            $date = date('Y-m-d H:i');
        }
        if ('YESTERDAY' === strtoupper($date)) {
            $date = date('Y-m-d H:i', strtotime('-1 day'));
        }
        if('AFTERFIVEDAYS' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('+5 day'));
        }
        if('TWOMONTHSAGO' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('-60 day'));
        }
        if('THREEDAYSAGO' === strtoupper($date))
        {
            $date = date('Y-m-d', strtotime('-3 day'));
        }
        if('FIVEMINUTESAGO' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('-5 minutes'));
        }
        if('TWOMINUTESLATER' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('+2 minutes'));
        }
        if('TODAY' === strtoupper($date))
        {
            $date = date('Y-m-d');
        }
        if('TODAYMIDNIGHT' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('00.00'));
        }
        if ('AFTERONEMONTHMIDNIGHT' === strtoupper($date)) {
            $date = date('Y-m-d H:i', strtotime('+1 month 00.00'));
        }
        if ('AFTERTENDAYSMIDNIGHT' === strtoupper($date))
        {
            $date = date('Y-m-d H:i', strtotime('+10 day 00.00'));
        }
        return $date;
    }

    public function getDateSecond($date)
    {
        switch (strtoupper($date)){
          case  'YESTERDAY':
              return $date = date('Y-m-d H:i:s',strtotime('-1 day'));
          case 'TOMORROW':

              return $date = date('Y-m-d H:i:s',strtotime('+1 day'));
       }
    }

    private function getRangeDateAssertion($campStartDate,$dateBegin,$dateEnd)
    {
        $this->assertTrue(($campStartDate >= $dateBegin) && ($campStartDate <= $dateEnd));
    }

    /**
     * clicks locator web object.
     *
     * @param $locator
     *
     * @throws \RuntimeException
     */
    public function check($locator)
    {
        $this->getElement($locator)->check();
    }

    /**
     * mouseOver locator web object.
     *
     * @param $locator
     *
     * @throws \RuntimeException
     */
    public function mouseOverElement($locator)
    {
        $this->getElement($locator)->mouseOver();
    }

    /**
     * clicks locator web object.
     *
     * @param $linkText
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function clickLink($linkText)
    {
        $this->getSession()->getPage()->clickLink($linkText);
    }


    /**
     * select option from locator web object
     *
     * @param $locator
     * @param $option
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function select($locator, $option)
    {
        $this->waitForElementVisible($locator);
        $this->getElement($locator)->selectOption($option);
    }

    /**
     * @param $locator - id|name|label|value
     * @param $option - select option from locator web object
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectByOption($locator, $option)
    {
        $this->getElement($locator)->selectFieldOption($locator, $option);
    }

    /**
     * type value into locator web object
     *
     * @param $locator
     * @param $value
     */
    public function type($locator, $value)
    {
        $value = $value === 'BLANK_STRING' ? '' : $value;
        $this->waitForElementVisible($locator);
        //$this->getElement($locator)->focus();
        $this->getElement($locator)->setValue($value);
    }

    public function iWriteTextIntoField($field, $text)
    {
        $this->getSession()
            ->getDriver()
            ->getWebDriverSession()
            ->element('xpath', $field)
            ->postValue(['value' => [$text]]);
    }

    /**
     * Input a number with a specific number of digits
     * into a given locator
     *
     * @param $locator
     * @param $digits
     */
    public function inputRandomNumber($locator, $digits)
    {
        $value = str_pad(rand(1, (10 ^ $digits) - 1), $digits, '0', STR_PAD_LEFT);
        $this->type($locator, $value);
    }

    /**
     * Reset the locator
     * @param $locator
     */
    public function reset($locator)
    {
        $this->waitForElementVisible($locator);
        $this->getElement($locator)->setValue('');
    }

    /**
     * attach file to the input
     *
     * @param $locator
     * @param $value
     */
    public function attachFile($locator, $value)
    {
        $this->getElement($locator)->attachFile($value);
    }

    /**
     * press escape button on the element
     *
     * @param $locator
     */
    public function pressEscapeOnElement($locator)
    {
        $this->getElement($locator)->keyPress(Key::ESCAPE);
    }

    /**
     * visit URL url
     *
     * @param $url
     */
    public function visitURL($url)
    {
        $this->visitPath($url);
    }

    /**
     * This function return the index xpath for the selected option
     * @param $i
     * @return string
     */
    public function getOption($i)
    {
        return '//li[' . $i . ']';
    }

    public function getOptionTool($i)
    {
        return $this->opt_select_tools . ':nth-child(' . $i . ')';
    }

    public function selectOption($locator, $value)
    {
        $this->click($locator);
        $this->type($locator, $value);
        $this->waitForElementVisible($this->opt_select, 60);

        $count = $this->getElementsCount($this->opt_select);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $this->getOption($i) . $this->opt_value;
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    /**
     * @param $array
     * @param $key
     */
    public function clickOnGroupedItem($array, $key)
    {
        if (isset($array[$key])) {
            $this->waitForElement($array[$key]);
            $this->click($array[$key]);
        }
    }

    /**
     * @param $locator
     * @param $value
     */
    public function clearSelect2($locator)
    {
        $locator = preg_replace('/^(.*) a$/', '$1', $locator);
        $this->getSession()->executeScript("$('" . addslashes($locator) . "').select2('val', '')");
    }

    public function selectUnitByIndex($index)
    {
        $this->waitForElement($this->opt_select);
        $this->click($this->getOption($index) . $this->opt_value);

    }

    public function selectOptionOne($locator, $value)
    {
        $this->click($locator);
        $this->type($locator, $value);
        $this->waitForElement($this->opt_select);
        $this->wait(5);
        $this->click($this->getOption('1') . $this->opt_value);

    }

    public function selectOptionByIndex($locator, $index)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select);
        $this->wait(5);
        $this->click($this->getOption($index) . $this->opt_value);
    }

    public function selectOptionByValue($locator, $value)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select);

        $count = $this->getElementsCount($this->opt_select);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $this->getOption($i) . $this->opt_value;
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    public function selectOptionByValueTools($locator, $value)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select_tools);

        $count = $this->getElementsCount($this->opt_select_tools);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $this->getOptionTool($i);
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    /**
     * This function return the index css selector for the selected option
     * @param $i
     * @return string
     */
    public function getSelectorIndex($i)
    {
        return ' li:nth-child(' . $i . ')';
    }

    //WORKS ONLY WITH CSS SELECTORS
    public function selectOptionByValueNew($locator, $value)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select_new);
        $dropdownList = str_replace("container", "results", $locator);
        $this->wait(1);
        $count = $this->getElementsCount($this->opt_select_new);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $dropdownList . $this->getSelectorIndex($i);
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    public function selectOptionByValueNewWithoutClick($locator, $value)
    {
        $this->waitForElement($this->opt_select_new);
        $dropdownList = str_replace("container", "results", $locator);
        $this->wait(1);
        $count = $this->getElementsCount($this->opt_select_new);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $dropdownList.$this->getSelectorIndex($i);
            if (strpos($this->getElementText($selectLoc), $value)=== 0) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    public function selectOptionByValueNested($locator, $value)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select_nested);
        $dropdownList = str_replace("container", "results", $locator);
        $this->wait(1);
        $count = $this->getElementsCount($this->opt_select_nested);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = '(' . $dropdownList . '/li/ul/li)' . '[' . $i . ']';
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    //Only works with CSS selectors, NOT XPATH
    public function selectOptionByIndexNew($locator, $index)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select_new);
        $dropdownList = str_replace("container", "results", $locator);
        $count = $this->getElementsCount($this->opt_select_new);
        $this->wait(3);
        $this->click($dropdownList . $this->getSelectorIndex($index));
    }

    public function selectOptionByTypingNew($locator, $value)
    {
        $this->click($locator);
        $this->iWriteTextIntoField($this->opt_select_textField, $value);
        $dropdownList = str_replace("container", "results", $locator);
        $selectLoc = $dropdownList . $this->opt_highlighted;
        $this->waitForElement($selectLoc, 10000);
        if (0 == strpos($this->getElementText($selectLoc), $value)) {
            $this->click($selectLoc);
        }
    }

    public function selectOptionByTypingNoDropdown($locator, $value, $noId = false)
    {
        $textField = $locator . $this->inputField;
        if ($noId) $textField = $this->opt_select_textField;

        $this->click($locator);
        $this->iWriteTextIntoField($textField, $value);
        $this->waitForElementVisible($this->opt_highlighted);
        $this->click($this->opt_highlighted);
    }

    public function selectOptionByTypingNoDropdownSmall($locator, $value)
    {
        $this->click($locator);
        $this->iWriteTextIntoField($locator . $this->inputFieldSmall, $value);
        $this->wait(2);
        $this->waitForElementVisible($this->opt_highlighted);
        $this->click($this->opt_highlighted);
    }

    public function selectOptionSpan($locator, $value)
    {
        $this->click($locator);
        $this->type($locator, $value);
        $this->waitForElement($this->opt_select);

        $count = $this->getElementsCount($this->opt_select);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $this->getOption($i) . $this->opt_value . '/span';
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }
        }
    }

    public function selectStoreOption($locator, $value)
    {
        $this->click($locator);
        $this->type($locator, $value);
        $this->waitForElement($this->opt_select);

        $count = $this->getElementsCount($this->opt_select);
        for ($i = 1; $i <= $count; $i++) {
            $selectLoc = $this->opt_store_loc . '[' . $i . ']/div';
            if (0 == strcmp($this->getElementText($selectLoc), $value)) {
                $this->click($selectLoc);
                break;
            }

        }
    }

    public function selectSubOption($locator, $value)
    {
        $this->click($locator);
        $this->waitForElement($this->opt_select);
        $count1 = $this->getElementsCount($this->opt_select_1);
        for ($i = 1; $i <= $count1; $i++) {
            $count2 = $this->getElementsCount($this->opt_select_2);
            for ($j = 1; $j <= $count2; $j++) {
                $selLoc = $this->opt_value_1 . '[' . $i . ']' . $this->opt_value_2 . '[' . $j . ']' . $this->opt_sub_select;
                if (0 == strcmp($this->getElementText($selLoc), $value)) {
                    $this->click($selLoc);
                    break;
                }
            }
        }
    }

    public function waitForElement($element, $wait = null)
    {
        $waitTime = ($wait !== null ? $wait : $this->short_wait);
        $this->getSession()->wait($waitTime, "$('$element').length");
    }

    public function waitForElementNotDisabled($element, $waitSeconds = 1000)
    {
        $this->waitForElementVisible($element);
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            if ($this->getElement($element)->getAttribute('disabled') !== 'disabled') {
                return;
            }
            $this->wait(0.25);
        }
        throw new \RuntimeException('Element ' . $element . ' not enabled.');
    }

    /**
     * @param float|int|null $waitSeconds
     * @param string $element
     */
    public function waitForNoElement($element, $waitSeconds = null)
    {
        if ($waitSeconds === null) {
            $this->getSession()->wait($this->short_wait, '$("' . $element . '").length=0');
        } else {
            $this->getSession()->wait($waitSeconds, '$("' . $element . '").length=0');
        }

    }

    /**
     * @param string $element
     */
    public function waitAMinForNoElement($element)
    {
        $this->getSession()->wait($this->short_wait, "$('$element').length=0");
    }

    /**
     * @param string $element
     */
    public function waitAnHourForNoElement($element)
    {
        $this->getSession()->wait($this->hour_wait, '$("' . $element . '").length=0');
    }

    /**
     * @param string $element
     *
     * @return bool
     */
    public function isElementExist($element)
    {
        $var = $this->getSession()->getPage()->find($this->getSelector($element), $element);
        return isset($var);
    }

    public function waitForElementVisible($element, $waitSeconds = 20)
    {
        $this->waitForElementExist($element, $waitSeconds);
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            try {
                if ($elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
                    if ($elem->isVisible()) {
                        return;
                    }
                }
            } catch (Exception\StaleElementReference $e) {
                continue;
            }
            $this->wait(0.25);
        }
        throw new \RuntimeException('Element ' . $element . ' not visible.');
    }
    public function isElementVisible($element)
    {
        // $this->waitForElementExist($element);
        if ($elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
            return $elem->isVisible();
        }

        return false;
    }

    /**
     * @param        $selector
     * @param string $selectorType
     */
    public function isFormElementTypeHidden($element)
    {
        /** @var NodeElement $elem */
        if ($elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
            $this->assertTrue($elem->hasAttribute('type'));
            $this->assertEquals($elem->getAttribute('type'), 'hidden');
        }
    }

    public function waitForElementExist($element, $waitSeconds = 30)
    {
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            try {
                if ($this->isElementExist($element)) {
                    return;
                }
            } catch (Exception\StaleElementReference $e) {
                continue;
            }
            $this->wait(0.25);
        }

        throw new \RuntimeException('Element ' . $element . ' doesn\'t exist.');
    }

    /**
     * @param $element
     * @param $elementText
     * @param int $waitSeconds
     * Method to wait for the text of the element to become equal to the given text
     */
    public function waitForElementTextEquals($element, $elementText, $waitSeconds = 30)
    {
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            if ($elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
                if ((string)$this->getElementText($element) === (string)$elementText) {
                    return;
                }
            }
            $this->wait(0.25);
        }

        throw new \RuntimeException('Element' . $element . ' doesn\'t match the text' . $elementText . 'after' . $waitSeconds . 'seconds of wait' );
    }

    public function waitForLinkVisible($text, $waitSeconds = 30)
    {
        $link = $this->getLink($text);
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            if ($link->isVisible()) {
                break;
            }
            $this->wait(0.25);
        }
    }

    public function waitForElementNotVisible($element, $waitSeconds = 10)
    {
        $this->waitForElementExist($element);
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            if (!$elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
                break;
            }
            if (!$elem->isVisible()) {
                break;
            }

            $this->wait(0.25);
        }
    }

    public function waitUnitElementGone($element, $waitSeconds = 10)
    {
        for ($j = 0; $j <= $waitSeconds * 4; $j++) {
            try{
                if (!$elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
                    break;
                }
                if (!$elem->isVisible()) {
                    break;
                }
            } catch (Exception\StaleElementReference $e)
            {
                continue;
            } catch (Exception\NoSuchElement $e)
            {
                continue;
            }
            $this->wait(0.25);
        }
    }

    public function getLink($text)
    {
        return $this->getSession()->getPage()->findLink($text);
    }

    /**
     * get the count of elements on web page
     *
     * @param $element
     *
     * @return string
     */
    public function getElementsCount($element)
    {
        return $this->getSession()->evaluateScript("return $('" . addslashes($element) . "').length");
    }

    /**
     * get focus of elements on web page
     *
     * @param $element
     */
    public function getElementFocus($element)
    {
        $this->getSession()->executeScript("$('" . addslashes($element) . "').focus");
    }

    public function enterTab($element)
    {
        $this->getSession()->executeScript("$('" . addslashes($element) . "').trigger($.Event('keypress', {which: 9, keyCode: 9}))");
    }

    public function scrollElement($element)
    {
        $this->getSession()->executeScript('arguments[0].scrollIntoView(true);' . $element);
    }

    public function scrollToBottom()
    {
        $this->getSession()->executeScript('window.scrollTo(0,document.body.scrollHeight);');
    }

    public function scrollTop()
    {
        $this->getSession()->executeScript('window.scrollTo(0,0);');
    }

    public function scrollIntoView($elementId)
    {
        $function = '(function(){var elem = document.getElementById("' . addslashes($elementId) . '"); elem.scrollIntoView(false);})()';
        try {
            $this->getSession()->executeScript($function);
        } catch (\Exception $e) {
            throw new \LogicException('ScrollIntoView failed');
        }
    }

    /**
     * Scroll to the bottom until the element is in view
     *
     * @param $elementCssSelector
     */
    public function scrollBottomIntoView($elementCssSelector)
    {
        $function = '(function(){var elem = document.querySelector("' . addslashes($elementCssSelector) . '"); elem.scrollIntoView(true);})()';
        try {
            $this->getSession()->executeScript($function);
        } catch (\Exception $e) {
            throw new \LogicException('ScrollBottomIntoView failed');
        }
    }

    /**
     * Scroll to the top until the element is in view
     *
     * @param $elementCssSelector
     */
    public function scrollTopIntoView($cssSelector)
    {
        $function = '(function(){var elem = document.querySelector("' . addslashes($cssSelector) . '"); elem.scrollIntoView(false);})()';
        try {
            $this->getSession()->executeScript($function);
        } catch (\Exception $e) {
            throw new \LogicException('ScrollTopIntoView failed');
        }
    }

    public function pressTab($locator)
    {
        $focusBlurDetector = $this->getElement($locator);
        $focusBlurDetector->blur();
    }

    public function refreshThePage()
    {
        $this->getSession()->reload();
    }

    /**
     * get element text visible ob the web page.
     *
     * @param $locator
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getElementText($locator)
    {
        return $this->getElement($locator)->getText();
    }
    /**
     * get element text href value ob the web page.
     *
     * @param $locator
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getElementhrefValue($locator)
    {
        return $this->getElement($locator)->getAttribute("href");
    }
    /**
     * get element text Attribute value ob the web page.
     *
     * @param $locator
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getAttributeValue($locator,$attribute)
    {
        return $this->getElement($locator)->getAttribute($attribute);
    }
    /**
     * Checks that specific element contains text and returns a bool.
     *
     * @param string $locator element selector type (css, xpath)
     * @param string $text expected text
     *
     */
    public function verifyElementTextContains($locator, $text)
    {
        $elementText = $this->getElement($locator)->getText();
        $regex = '/' . preg_quote($text, '/') . '/ui';

        return preg_match($regex, $elementText);
    }

    public function getElementValue($locator)
    {
        return $this->getElement($locator)->getValue();
    }

    public function getFocus($control)
    {
        $this->getSession()->getDriver()->focus($control);
    }

    public function pressEscape()
    {
        $this->getSession()->executeScript("$(':focus').trigger($.Event('keypress', {which: 27, keyCode: 27}));");
    }

    public function getWindowName()
    {
        return $this->getSession()->getWindowName();
    }

    public function switchWindow($window)
    {
        $this->getSession()->switchToWindow($window);
    }
    /********************** Assert Functions ***********************************************/

    /**
     * assert - page text not contains text 'page not found'
     *
     * @throws \Behat\Mink\Exception\ResponseTextException
     */
    public function assertPageNotFound()
    {
        $this->assertSession()->pageTextNotContains('Page not found');
    }

    /**
     * assert - element label is matching with the searchText
     *
     * @param $locator
     * @param $searchText
     *
     * @throws \Behat\Mink\Exception\ElementTextException
     */
    public function assertElementLabel($locator, $searchText)
    {
        $this->waitForElementVisible($locator);
        $this->assertSession()->elementTextContains($this->getSelector($locator), $locator, $searchText);
    }


    /**
     * assert - current page URL is matching with url
     *
     * @param $url
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertPageURL($url)
    {
        $this->assertSession()->addressEquals($url);
    }

    /**
     * get - current page URL
     */
    public function getPageURL()
    {
        return $this->getSession()->getDriver()->getCurrentUrl();
    }


    /**
     * assert - current page URL is matching with url
     *
     * @param $url
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertMatchPageURL($url)
    {
        $this->assertSession()->addressMatches('/' . str_replace('/', '\/', $url) . '/');
    }

    /**
     * maximize current browser window
     */
    public function maximizeWindow()
    {
        $this->getSession()->maximizeWindow();
    }

    /**
     * assert - element is visible on current web page
     * @param $element
     * @throws \RuntimeException
     * @return boolean
     */
    public function assertElementVisible($element)
    {
        if ($elem = $this->getSession()->getPage()->find($this->getSelector($element), $element)) {
            return $elem->isVisible();
        }

        throw new \RuntimeException('Element "' . $element . '" not visible');
    }

    /**
     * assert - element is not visible on current web page
     *
     * @param $locator
     *
     * @return $this
     * @throws \RuntimeException
     */
    public function assertElementNotVisible($locator)
    {
        if (!$this->isElementVisible($locator)) {
            return $this;
        }

        throw new \RuntimeException('Unexpected Element "' . $locator . '" visible on the page');
    }

    /**
     * @param $locator
     *
     * @return mixed
     */
    public function getSelector($locator)
    {
        return (($locator[0] === '/') or ($locator[0] === '(')) ? 'xpath' : 'css';
    }

    /**
     * @param float|int $seconds
     */
    public function wait($seconds)
    {
        $this->getSession()->wait($seconds * 1000);
    }

    public function typeTab($locator)
    {
        $this->type($locator, Key::TAB);
    }

    public function enableSection($section, $button)
    {
        if ($this->getElement($section)->getAttribute('class') === 'inactive') {
            $this->click($button);
        }
    }

    public function disableSection($section, $button)
    {
        if ($this->getElement($section)->getAttribute('class') === 'active') {
            $this->click($button);
        }
    }

    /**
     * @param $button
     *
     * @throws \RuntimeException
     */
    public function disableManageTypes($button)
    {
        if ($this->isManageTypeButtonEnabled($button)) {
            $this->click($button);
        }
    }

    /**
     * @param $button
     *
     * @throws \RuntimeException
     */
    public function enableManageTypes($button)
    {
        if (!$this->isManageTypeButtonEnabled($button)) {
            $this->click($button);
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @throws \RuntimeException
     */
    public function assertEquals($actual, $expected)
    {
        if ((string)$expected !== (string)$actual) {
            throw new \UnexpectedValueException('Assert Failed!' . PHP_EOL . 'Actual: "' . $actual . '" but Expected == "' . $expected . '"');
        }
    }

    /**
     * @param mixed $actual
     *
     * @param array $expectedValues
     * @internal param mixed $expected
     */
    public function assertEqualsMultiple($actual, $expectedValues = [])
    {
        $match = false;
        foreach ($expectedValues as $value) {
            if ((string)$actual === (string)$value) {
                $match = true;
            }
        }

        if (!$match) {
            throw new \UnexpectedValueException('Assert Failed!' . PHP_EOL . 'Actual: "' . $actual . '" but Expected: "' . implode(' ', $expectedValues) . '"');
        }
    }

    /**
     * @param mixed $expected
     * @param mixed $actual
     *
     * @throws \RuntimeException
     */
    public function assertNotEquals($actual, $expected)
    {
        if ((string)$expected === (string)$actual) {
            throw new \UnexpectedValueException('Assert Failed!' . PHP_EOL . 'Actual: "' . $actual . '" but Expected <> "' . $expected . '"');
        }
    }

    /**
     * @param mixed $actual
     *
     * @param array $expectedValues
     * @internal param mixed $expected
     */
    public function assertNotEqualsMultiple($actual, $expectedValues = [])
    {
        $match = false;
        foreach ($expectedValues as $value) {
            if ((string)$actual !== (string)$value) {
                $match = true;
            }
        }

        if (!$match) {
            throw new \UnexpectedValueException('Assert Failed!' . PHP_EOL . 'Actual: "' . $actual . '" but Expected <> "' . implode(' ', $expectedValues) . '"');
        }
    }

    /**
     * @param mixed $actual
     *
     * @throws \RuntimeException
     */
    public function assertTrue($actual)
    {
        if (!$actual) {
            throw new \UnexpectedValueException('Assert Failed!' . PHP_EOL . 'Actual: false but Expected: true');
        }
    }


    /**
     * @param $locator
     *
     * @return NodeElement
     * @throws \RuntimeException
     */
    public function getElement($locator)
    {
        $control = $this->getSession()->getPage()->find($this->getSelector($locator), $locator);
        if ($control === null) {
            throw new \RuntimeException('Element "' . $locator . '" not found.');
        }

        return $control;
    }


    /**
     * @param $index
     * @param $element
     * @return mixed
     */
    public function getWordByIndexInElement($index, $element)
    {
        return explode(' ', trim($this->getElementText($element)))[$index];
    }

    /**
     * @param $locator
     *
     * @return NodeElement
     * @throws \RuntimeException
     */
    public function getElements($locator)
    {
        $control = $this->getSession()->getPage()->findAll($this->getSelector($locator), $locator);
        if ($control === null) {
            throw new \RuntimeException('Element "' . $locator . '" not found.');
        }

        return $control;
    }

    public function alertOK()
    {
        /** @var \Behat\Mink\Driver\Selenium2Driver $Selenium2Driver */
        $Selenium2Driver = $this->getSession()->getDriver();
        $Selenium2Driver->getWebDriverSession()->accept_alert();
    }

    public function alertCancel()
    {
        /** @var \Behat\Mink\Driver\Selenium2Driver $Selenium2Driver */
        $Selenium2Driver = $this->getSession()->getDriver();
        $Selenium2Driver->getWebDriverSession()->dismiss_alert();
    }

    public function isManageTypeButtonEnabled($button)
    {
        return $this->getElement($button)->getAttribute('style') === 'width: 30px; left: 30px;';
    }

    /**
     * @param $searchBy
     * @param $campaignName
     * @throws \Behat\Mink\Exception\ElementTextException
     * @throws \RuntimeException
     */
    public function verify_search($searchBy, $campaignName)
    {
        $maxNumberOfPages = 10; //added this because we run out of memory when we have too many pages to check
        $page = 0;

        $this->waitUnitElementGone($this->lbl_loading);
        $nameHeader = array_search($searchBy, $this->getResultHeader(), true);
        while ($this->isElementExist($this->btn_next) && $page < $maxNumberOfPages) {
            $rowCount = $this->getElementsCount($this->tbl_result_row);
            for ($i = 1; $i <= $rowCount; $i++) {
                $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
                if ($this->isElementVisible($control)) {
                    $this->assertElementLabel($control, $campaignName);
                }
            }
            $this->scrollToBottom();
            if (!$this->isElementExist($this->btn_next_disabled)) {
                $this->click($this->btn_next);
                $page++;
            }
            $this->waitUnitElementGone($this->lbl_loading);
        }

        $rowCount = $this->getElementsCount($this->tbl_result_row);
        $this->assertElementNotVisible($this->no_data_in_table);
        for ($i = 1; $i <= $rowCount; $i++) {
            $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
            if ($this->isElementVisible($control)) {
                $this->assertElementLabel($control, $campaignName);
            }
        }
    }

    /**
     * @param $searchBy
     * @throws \Behat\Mink\Exception\ElementTextException
     * @throws \RuntimeException
     */
    public function addElementsInTableColumn($searchBy)
    {
        $nameHeader = (int)array_search($searchBy, $this->getResultHeader(), true);
        $secondControl = 0;
        while ($this->isElementExist($this->btn_next)) {
            $rowCount = $this->getElementsCount($this->tbl_result_row);
            for ($i = 1; $i <= $rowCount; $i++) {
                $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
                if ($this->isElementVisible($control)) {
                    $firstControl = (int)$this->getElementText($control);
                    $secondControl += $firstControl;
                }
            }
            $this->scrollToBottom();
            if (!$this->isElementExist($this->btn_next_disabled)) {
                $this->click($this->btn_next);
            }
            $this->waitForNoElement($this->lbl_loading, 5);
        }

        $rowCount = $this->getElementsCount($this->tbl_result_row);
        $this->assertElementNotVisible($this->no_data_in_table);
        for ($i = 1; $i <= $rowCount; $i++) {
            $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
            if ($this->isElementVisible($control)) {
                $firstControl = (int)$this->getElementText($control);
                $secondControl += $firstControl;
            }

        }
        return $secondControl;
    }

    /**
     * @param $searchBy
     */
    public function getElementInTableByHeader($searchBy)
    {
        $nameHeader = array_search($searchBy, $this->getResultHeader(), true);
        $rowCount = $this->getElementsCount($this->tbl_result_row);
        $control = $this->tbl_result_row_x . '[1]/td[' . $nameHeader . ']';
        return $this->getElementText($control);
   }

    /**
     * @param $searchBy
     * @param $campaignName
     * @throws \Behat\Mink\Exception\ElementTextException
     * @throws \RuntimeException
     */
    public function verify_campaign_type_history_search($searchBy, $campaignName)
    {
        $this->waitUnitElementGone($this->lbl_loading);
        $nameHeader = array_search($searchBy, $this->getCampaignTypeResultHeader(), true);

        while ($this->isElementExist($this->btn_next)) {
            $rowCount = $this->getElementsCount($this->tbl_result_row);
            for ($i = 1; $i <= $rowCount; $i++) {
                $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
                if($this->isElementVisible($control)) {
                    $this->assertElementLabel($control, $campaignName);
                }
            }
            $this->scrollToBottom();
            if(!$this->isElementExist($this->btn_next_disabled)){
                $this->click($this->btn_next);
            }
            $this->waitUnitElementGone($this->lbl_loading);
        }

        $rowCount = $this->getElementsCount($this->tbl_result_row);
        $this->assertElementNotVisible($this->no_data_in_table);
        for ($i = 1; $i <= $rowCount; $i++) {
            $control = $this->tbl_result_row_x . '[' . $i . ']/td[' . $nameHeader . ']';
            if ($this->isElementVisible($control)) {
                $this->assertElementLabel($control, $campaignName);
            }
        }
    }


    public function randomNumber($digits)
    {
        $x = $digits - 1;
        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;

        return mt_rand($min, $max);
    }

    public function randomPhoneNumber()
    {
        $prefix = '073';
        $number = $this->randomNumber(8);

        return $prefix . $number;
    }

    /**
     * @param array $row
     * @param $find
     * @return int
     */
    public function checkCells(array $row, $find)
    {
        foreach ($row as $cell => $cellData) {
            if (is_string($cellData) && $cellData !== '' && $cellData !== null) {

                if (in_array($cellData[0], $find, true)) {
                    return $cellData[0];
                }
            }
        }
        return 0;
    }

    public function convertPhoneNumberToStandard($phoneNumber, $areaCode = "+44")
    {
        if ($phoneNumber[0] == '0') {
            $phoneNumber = substr($phoneNumber, 1);
        }

        return $areaCode . $phoneNumber;
    }

    public function convertPartialIdToFullCssSelector($rule)
    {
        $element = $this->getElement($rule);
        return '#' . $element->getAttribute('id');
    }

    public function randomNumberLessThan($integer)
    {
        return mt_rand(0, $integer - 1);
    }

    public function randomGreaterThan10($integer)
    {
        return mt_rand(10, $integer);
    }

    public function randomGreaterThan100($integer)
    {
        return mt_rand(100, $integer);
    }

    public function randomGreaterThan10000000($integer)
    {
        return mt_rand(10000000, $integer);
    }


    public function getTestDataFilePath($path)
    {
        $rootdir = realpath(dirname(__FILE__) . '/..');
        $fullPath = $rootdir . '/InputData/' . ltrim($path, '/');
        $findSeperator = '/';
        if (DIRECTORY_SEPARATOR !== '/') {
            $findSeperator = '//';
        }
        return str_replace($findSeperator, DIRECTORY_SEPARATOR, $fullPath);
    }

    /**
     * @param string $imagePath
     * @param string $relativePath
     * @return mixed
     */
    public function getImageAbsolutePath($imagePath, $relativePath = '/app/test/Data/')
    {
        $fullPath = $relativePath . ltrim($imagePath, '/');
        $findSeparator = '/';
        if (DIRECTORY_SEPARATOR !== '/') {
            $findSeparator = '\\';
        }

        return str_replace($findSeparator, DIRECTORY_SEPARATOR, $fullPath);
    }

    /**
     * @param $control
     *
     * @throws \RuntimeException
     */
    public function waitAndClick($control)
    {
        $this->waitForElementVisible($control);
        $this->click($control);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->randomGenerator($length, $this->characters);
    }

    /**
     * @param $length
     *
     * @return string
     */
    public function generateRandomInteger($length)
    {
        return $this->randomGenerator($length, $this->integers);
    }

    /**
     * @param $length
     *
     * @return string
     */
    public function generateRandomSpecialCharacter($length)
    {
        return $this->randomGenerator($length, $this->specialCharacters);
    }

    /**
     * @param int $length
     * @param string $characterSet
     *
     * @return string
     */
    public function randomGenerator($length, $characterSet)
    {
        $charactersLength = strlen($characterSet) - 1;
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characterSet[mt_rand(0, $charactersLength)];
        }

        return $randomString;
    }

    public function setDataPickerValue($control, $date)
    {
        try {
            if ('afterOneMonth' === $date) {
                $date = date('Y-m-d H:i', strtotime('+1 month'));
            }
            if ('currentDate' === $date) {
                $date = date('Y-m-d 23:59');
            }
            print_r("Entered Date --> " . $date);

            $this->type($control, $date);
            //$this->click($this->randomCampaignStartClick);
            $this->enterTab($control);
        } catch (\Exception $e) {
            print_r($e->getMessage());
            throw $e;
        }
    }
    /**
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     *
     */
    public function getChildWindow()
    {
        $window = $this->getSession()->getDriver()->getWindowNames();
        $this->getSession()->getDriver()->switchToWindow($window[1]);
    }
    /**
     * Docker specific method that calls a socket on a second docker container to trigger a task at specific sequence
     *
     * @param $env
     * @param $command
     * @param $domain
     *
     * @return null
     */
    public function runRemoteHandsCommand($env, $command, $domain)
    {
        $error = null;
        $attempts = 0;
        $timeout = 2;
        $connected = null;
        $errstr = '';

        $hostname = gethostname();

        // ensure this ONLY runs on docker
        if ($hostname === 'air-test-auto' && strtolower($env) === 'local') {
            $port = '49597';
            $address = preg_replace('#^https?://#', '', $domain);

            if (!($sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
                $errstr = 'Error Creating Socket: ' . socket_strerror(socket_last_error());

                return null;
            }

            $timeout *= 1000;
            while (!($connected = @socket_connect($sock, $address, $port)) && $attempts++ < $timeout) {
                $error = socket_last_error();
                if ($error !== SOCKET_EINPROGRESS && $error !== SOCKET_EALREADY) {
                    $errstr = 'Error Connecting Socket: ' . socket_strerror($error);
                    socket_close($sock);

                    return null;
                }
                usleep(1000);
            }

            if (!$connected) {
                $errstr = 'Error Connecting Socket: Connect Timed Out After $timeout seconds. ' . socket_strerror(socket_last_error());
                socket_close($sock);

                return null;

            }

            socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, 1); //Set
            $ret = socket_write($sock, $command, strlen($command)); //Send data
            socket_close($sock);
            if ($ret <= 0) {
                return null;

            }
        }
    }

    /**
     * @param $path
     * @param $domain
     *
     * @return string
     */
    public function downloadFile($path, $domain)
    {

        $driver = $this->getSession()->getDriver();
        if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
            $ds = $driver->getWebDriverSession();
            $cookies = $ds->getAllCookies();
        } else {
            throw new \InvalidArgumentException('Not Selenium2Driver');
        }

        $cookieJar = new \GuzzleHttp\Cookie\CookieJar(true);

        foreach ($cookies as $cookie) {
            $cookie = array_combine(
                array_map('ucfirst', array_keys($cookie)),
                array_values($cookie));

            $newCookie = new \GuzzleHttp\Cookie\SetCookie($cookie);
            /**
             * You can also do things such as $newCookie->setSecure(false);
             */
            $cookieJar->setCookie($newCookie);
        }

        $client = new \GuzzleHttp\Client([
            'base_uri' => $domain,
            'cookies' => $cookieJar
        ]);

        $response = $client->request('GET', $path, ['stream' => true, 'verify' => false]);

        $body = $response->getBody();

        $file = '';
        while (!$body->eof()) {
            $file .= $body->read(1024);
        }
        return $file;
    }

    /**
     * This function checks for error messages
     * @param $selector
     * @param $errorMessage
     */
    public function checkForErrorMessage($selector, $errorMessage)
    {
        $this->assertElementExist($selector);
        $this->assertElementVisible($selector);
        $this->assertSession()->elementTextContains('css', $selector, $errorMessage);
    }

    /**
     * This creates the cookie header string for curl to send when making requests.
     * It includes the (PHP) session ID among possibly a few other cookies. This is
     * not for use with curl's built-in cookie JAR (CURLOPT_COOKIEJAR) and file
     * handling (CURLOPT_COOKIEFILE) options. This is using the curl header
     * (CURLOPT_COOKIE) with value of "name1=value1; name2=value2..."
     * See http://hasin.me/2007/04/18/cookiejar-in-curl-it-sucks/ and
     * http://en.wikipedia.org/wiki/HTTP_cookie for more details.
     *
     * The cookie header string is created by extracting cookies from the Selenium
     * RC browser session for use by curl. Can be adapted for WebDriver
     * @author David Luu
     * @return cookie string to pass to curl_setopt($ch, CURLOPT_COOKIE, $returnValueHere);
     */
    function createCurlSessionCookieFromSeleniumSession()
    {
        //this function assumes the function has reference to
        //Selenium RC object via PHPUnit-Selenium, modify as needed
        //get Selenium browser session for use by curl
        //replace PHPSESSID with equivalent session cookie you need
        //$session = $this->getCookieByName("PHPSESSID");

        //extract any additional cookies you need...

        $driver = $this->getSession()->getDriver();
        if ($driver instanceof \Behat\Mink\Driver\Selenium2Driver) {
            $ds = $driver->getWebDriverSession();
            $cookies = $ds->getAllCookies();
        } else {
            throw new \InvalidArgumentException('Not Selenium2Driver');
        }

        //build cookie string
        $cookieString = "PHPSESSID=" . $cookies[0]['value'];
        //append additional cookies as needed in this format
        //"name1=value1; name2=value2..." where last cookie no need ";"

        return $cookieString;
    }

    /**
     * Commenting out the entire function as it throws error on throw new exception line
     * " cannot instantiate abstract class " and this function is not used anywhere. 
     * Use curl w/ session cookie to download a file by URL and save to temp file on disk
     * @author David Luu
     * @param $fileUrl
     * @param $sessionCookie - get via createCurlSessionCookieFromSeleniumSession()
     * @return temp file name of downloaded file OR exception if download fails
     */
    /**function downloadFileByCurl($fileUrl, $sessionCookie)
    {
        //set up HTTP request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fileUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        /* we're not using curl built in cookie management as we need to
         * use cookies from Selenium and it's a pain to parse/convert
         * from Selenium cookie output to curl cookie file format
         * and read http://hasin.me/2007/04/18/cookiejar-in-curl-it-sucks/
         */
        //curl_setopt($ch, CURLOPT_COOKIEJAR, $this->myCookieFile);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $this->myCookieFile);
        //curl_setopt($ch, CURLOPT_COOKIE, $sessionCookie);

        //perform download of file from URL
/**
        $output = curl_exec($ch);
        curl_close($ch);

        //if download successful, write to file
        if ($output) {
            $tmpfname = tempnam("/tmp", "yourUniquePrefix");
            $handle = fopen($tmpfname, "w");
            fwrite($handle, $output);
            fclose($handle);
            return $tmpfname;
            //don't forget to delete or "unlink()" file after working with it
        } else {
            throw new Exception("Failed to download file $fileUrl for further testing.");
            //or die("Failed to download file $fileUrl for further testing.");
        }**/
    //}
    /**
     *
     */


    public function getDateLabel($labelName)
    {
        switch (strtoupper($labelName)) {
            case'YESTERDAY':
                return date('Y-m-d', strtotime('-1 day'));
            case'TODAY':
                return date('Y-m-d');
            case'WEEKTODATE':
                return date('Y-m-d', strtotime('-7 day'));
            case'MONTHTODATE':
                return date('Y-m-d', strtotime('-30 day'));
            case'BETWEEN':
                return date('Y-m-d', strtotime('-3 day'));
            default:
                return $labelName;
        }
    }

}
