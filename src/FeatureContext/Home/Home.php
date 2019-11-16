<?php
/**
 * Home.php
 *
 */

namespace FeatureContext\Home;

use Components\Helper;

class Home extends Helper
{

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

}
