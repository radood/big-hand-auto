<?php

/**
 * Created by PhpStorm.
 * User: radu.arsici
 *
 */

namespace FeatureContext\CustomerCare;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Tester\Exception\PendingException;
use Components\Helper;

class CustomerCare extends Helper implements Context
{

    /**
     * @param $firstName
     * @When User enter first name as :firstName on the Contact us Page
     */
    public function userEnterFirstNameContactUsPage($firstName)
    {
        $this->type($this->inp_first_name_contactUs, $firstName);
    }

    /**
     * @param $firstName
     * @When User click submit button on the Contact us Page
     */
    public function userClickSubmitButtonContactUsPage($firstName)
    {
        $this->click($this->btn_submit_contactUs);
    }

    /**
     * @param $firstName
     * @Then User should see an error message on the Contact us Page
     */
    public function assertUserSeesErrorOnContactUsPage($firstName)
    {
        $this->assertElementVisible($this->txt_error_contactUs);
    }
}