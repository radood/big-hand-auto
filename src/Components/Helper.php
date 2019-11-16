<?php
/**
 * Created by PhpStorm.
 *
 */

namespace Components;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Output\Node\EventListener;
use Behat\Testwork\Tester\Result\TestResult;
use DataProviderHelper\ConsumerData;
use DataProviderHelper\PlansData;
use DataProviderHelper\ProgrammesData;
use DataProviderHelper\RewardBankData;
use DataProviderHelper\SchemeData;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Component\Console\Input\ArgvInput;
use Faker;
use DataProviderHelper\CampaignData;
use DataProvider\Request;
use WebDriver\Exception;
use DataProviderHelper\WalletAccountData;

class Helper extends SeleniumHelper
{
    
    protected $default = 'DEFAULT';
    protected static $ENV = '';
    protected static $DOMAIN = '';
    protected static $USERNAME = '';
    protected static $PASSWORD = '';

    protected $logger;
    protected $data;


    public function __construct()
    {
        $this->data = Faker\Factory::create('en_GB');
    }



    /**
     * @BeforeScenario
     *
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     *
     * @throws \Behat\Behat\Context\Exception\ContextNotFoundException
     */
    public function before(BeforeScenarioScope $scope)
    {
        $this->currentScenario = $scope->getScenario();
    }

    // HOOKS

    /**
     * @BeforeFeature
     * @param BeforeFeatureScope $scope
     */
    public static function beforeFeature($scope)
    {
        $msg = "[%s] [%s] - Started Feature: %s" . PHP_EOL;
        $tStamp = new \DateTime('now');
        fwrite(STDOUT, sprintf($msg, $tStamp->format('Y-m-d H:i:s'), __CLASS__, $scope->getFeature()->getTitle()));
    }

    /**
     * @AfterFeature
     * @param AfterFeatureScope $scope
     */
    public static function afterFeature($scope)
    {
        $msg = "[%s] [%s] - Completed Feature: %s" . PHP_EOL;
        $tStamp = new \DateTime('now');
        fwrite(STDOUT, sprintf($msg, $tStamp->format('Y-m-d H:i:s'), __CLASS__, $scope->getFeature()->getTitle()));
    }


    /**
     * @BeforeScenario
     * @param BeforeScenarioScope $scope
     */
    public function beforeScenario($scope)
    {
        $msg = "[%s] [%s] - Started Scenario: %s" . PHP_EOL;
        $tStamp = new \DateTime('now');
        fwrite(STDOUT, sprintf($msg, $tStamp->format('Y-m-d H:i:s'), __CLASS__, $scope->getScenario()->getTitle()));
    }

    /**
     * @AfterScenario
     * @param afterScenarioScope $scope
     */
    public function afterScenario($scope)
    {
        $msg = "[%s] [%s] - Completed Scenario: %s" . PHP_EOL;
        $tStamp = new \DateTime('now');
        fwrite(STDOUT, sprintf($msg, $tStamp->format('Y-m-d H:i:s'), __CLASS__, $scope->getScenario()->getTitle()));
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function afterStep(AfterStepScope $scope)
    {
        var_dump(__LINE__);
        if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {

            $counter = $scope->getStep()->getText();

            $scenarioName = $scope->getFeature()->getTitle();
            $fileName = preg_replace('/\W/','',$scenarioName . $counter . date('YmdHi')).'.png';
            $findSeparator = '/';
            if (DIRECTORY_SEPARATOR !== '/') {
                $findSeparator = '\\';
            }
            $root = __DIR__ .'\..';
            $path = $root . '/OutputData';
            $path = str_replace('/', '\\', $path);
            if (!is_dir($path)) {
                return;
            }
            $this->saveScreenshot($fileName, $path);
        }
    }

    public function setupEnvironment($clientName = null)
    {
        $envName = $this->getEnvironmentName();
        switch (strtoupper($envName)) {
            case 'LIVE':
                Helper::$DOMAIN = $this->url_live;

                switch (strtoupper($clientName)) {
                    case 'QA':
                        Helper::$USERNAME = $this->usr_live_qa_username;
                        Helper::$PASSWORD = $this->usr_live_qa_password;

                        break;
                }
                break;

        }

        Helper::$ENV = $envName;
        // to make sure that all tests are run against one environment at a time
        // use the --profile=<env> in the command line argument
        if (Helper::$DOMAIN !== $this->getMinkParameter('base_url')) {
            Helper::$DOMAIN = $this->getMinkParameter('base_url');
        }

    }

    public static function getEnvironmentName()
    {
        $input = new ArgvInput($_SERVER['argv']);
        $profile = $input->getParameterOption(array('--profile', '-p')) ?: 'default';

        return $profile;
    }

    public function getEnvironmentVariable($param, $var)
    {
        return (strtoupper($param) == $this->default ? $var : $param);
    }

    public function getLoginUserName()
    {
        return Helper::$USERNAME;
    }

    public function getLoginPassword()
    {
        return Helper::$PASSWORD;
    }

    public function getDomain()
    {
        return Helper::$DOMAIN;
    }
}