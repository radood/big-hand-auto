<?php
namespace FeatureContext\Common;

/**
 * Created by PhpStorm.
 *
 */

use Components\Helper;

class Application extends Helper
{

    /**
     * @param $clientName
     * @Given Environment is set to client :clientName
     */

    public function set_environment($clientName)
    {
        // pass the env in via profile
        $this->setupEnvironment($clientName);
    }

    /**
     * @Given User request headers for the current URL
     */

    public function get_headers()
    {
        $this->getSession()->getStatusCode();

    }
    /**
     * @Given User launch Dashboard Application
     */

    public function launch_application()
    {
        $this->visitURL($this->getDomain());
        $this->maximizeWindow();
    }

    /**
     * @param int $seconds
     * @When User waits for :seconds seconds
     */
    public function wait_for_seconds( $seconds)
    {
        $this->wait($seconds);
    }

    /**
     * @param $filePath
     * @When User save html page source to :filePath
     */

    public function savePageSource($filePath)
    {
        file_put_contents($filePath, $this->getSession()->getPage()->getOuterHtml());
    }

    /**
     * @When User refresh the current session page
     */
    public function refreshCurrentSessionPage() {
        $this->refreshThePage();
        $this->wait(2);
    }


}