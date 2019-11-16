<?php
/**
 * Home.php
 *
 */

namespace FeatureContext\Home;

use Components\Helper;

class Home extends Helper
{
    protected $scenarioName = '';

    /**
     * @When User clicks on contact us button
     *
     * @throws \RuntimeException
     */

    public function user_clicks_contact_us()
    {
        $this->waitForElementVisible($this->btn_contactUs);
        $this->click($this->btn_contactUs);
    }

    /**
     * @Then Campaign menu should be visible to User
     *
     * @throws \RuntimeException
     */

    public function verify_campaign_menu_visible()
    {
        $this->assertElementVisible($this->mnu_campaigns);
    }

}
