<?php
/**
 * Created by PhpStorm.
 *
 */

namespace Common;


use Faker\Factory;
use Faker\Provider;

class FakeData
{
    public function __construct()
    {
        $this->faker = Factory::create('en_GB');
        $this->faker->addProvider(new Provider\en_GB\PhoneNumber($this->faker));
    }

    public function getTimeStamp()
    {
        return $this->faker->dateTime->getTimestamp();
    }

    public function getRandomNumber()
    {
        return $this->faker->numberBetween(100000, 999999);
    }
    public function getRandomNumberForFICO()
    {
        return $this->faker->numberBetween(1000000000, 1099999999);
    }
    public function getRandomNumberForSTOR()
    {
        return $this->faker->numberBetween(250000000, 259999999);
    }
    public function getRandomNumberWith4Digits()
    {
        return $this->faker->numberBetween(1000, 9999);
    }
    public function getRandomNumberWith5Digits()
    {
        return $this->faker->numberBetween(10000, 99999);
    }
    public function getRandomNumberWith6Digits()
    {
        return $this->faker->numberBetween(100000, 999999);
    }
    public function getRandomNumberWith7Digits()
    {
        return $this->faker->numberBetween(1000000, 9999999);
    }
    public function getRandomNumberWith8Digits()
    {
        return $this->faker->numberBetween(10000000, 99999999);
    }
    public function getRandomNumberWith9Digits()
    {
        return $this->faker->numberBetween(100000000, 999999999);
    }
    public function getRandomNumberWith11Digits()
    {
        return $this->faker->numberBetween(10000000000, 99999999999);
    }
    public function getRandomNumberWith18Digits()
    {
        return $this->faker->numberBetween(100000000000000000, 999999999999999999);
    }

    /**
     * @param $digits
     * @return int
     */
    public function getRandomNumberDigits($digits)
    {
        $temp = "";

        for ($i = 0; $i < $digits; $i++) {
            $temp .= rand(0, 9);
        }

        return (int)$temp;
    }

    /**
     * @param $length
     * @return string
     */
    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->faker->title;

    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->faker->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->faker->lastName;
    }

    /**
     * @return string
     */
    public function getDOB()
    {
        return $this->faker->date('Y-m-d');
    }

    /**
     * @return string
     */
    public function getGender()
    {
        $gender = 'MALE';
        return $gender;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->faker->email;
    }

    public function getCardNumber()
    {
        return $this->faker->creditCardNumber;
    }
    /**
     * @return string
     */
    public function getMobileNumber()
    {
        $values = array();
        for ($i=0; $i < 9; $i++) {
            // get a random digit, but always a new one, to avoid duplicates
            $values []= $this->faker->randomDigit;
        }
        return '447'.implode($values);
    }

    public function getTelephoneAreaCode()
    {
        $values = array();
        for ($i=0; $i < 2; $i++) {
            // get a random digit, but always a new one, to avoid duplicates
            $values []= $this->faker->randomDigit;
        }
        return ''.implode($values);
    }

    public function getTelephoneCountryCode()
    {
        return 'GB';
    }

    public function getTelephoneNumberType()
    {
        return 'MOBILE';
    }

    public function getUkMobileNumber()
    {
        $values = array();
        for ($i=0; $i < 9; $i++) {
            // get a random digit, but always a new one, to avoid duplicates
            $values []= $this->faker->randomDigit;
        }
        return '07'.implode($values);
    }
    /**
     * @return array
     */
    public function getAddress()
    {
        $line1 = $this->faker->buildingNumber;
        $line2 = $this->faker->streetName;
        $city = $this->faker->city;
        $county = $this->faker->streetSuffix;
        $postcode = $this->faker->postcode;
        $country = 'UK';
        $address = array(
            "addressLines" => array('Line1', 'Line2'),
            "postalCode"=>$postcode,
            "town_city"=>$city,
            #"county"=>$county,
            "country"=>$country,
            "province"=> 'Surrey'
        );
        return $address;
    }

    public function getOrderAddress()
    {
        $line1 = $this->faker->buildingNumber;
        $line2 = $this->faker->streetName;
        $city = $this->faker->city;
        $county = $this->faker->streetSuffix;
        $postcode = $this->faker->postcode;
        $country = 'UK';
        $address = array("line1"=>$line1, "line2"=>$line2, "line3"=>$line1,"line4"=>$line2,"city"=>$city, "postcode"=>$postcode, "county"=>$county, "country"=>$country);
        return $address;
    }

    public function getOrderPostalIdentity()
    {
        return array(
            "title"=>$this->faker->title,
            "firstName"=>$this->faker->firstName,
            "secondName"=>$this->faker->lastName,
            "line1"=>$this->faker->buildingNumber,
            "line2"=>$this->faker->streetName,
            "line3"=>$this->faker->buildingNumber,
            "line4"=>$this->faker->streetName,
            "city"=>$this->faker->city,
            "postcode"=>$this->faker->postcode,
            "county"=>$this->faker->streetSuffix,
            "country"=>'UK'
        );

    }

    public function getOrderPurchaser()
    {
        return array(
            "title" => $this->faker->title,
            "firstName" => $this->faker->firstName,
            "secondName" => $this->faker->lastName,
            "reference" => $this->faker->md5,
            //"phone" => $this->getUkMobileNumber(),
            "phone" => '07424767054',
            "emailAddress" => $this->getEmail(),
            "marketing" => true,
            "purchaserAddresses" => array($this->getOrderAddress()),
        );
    }
    public function getOrderTotal($total, $discount)
    {
        return array(
            "voucherTotal" => $total,
            "deliveryTotal" => 10,
            "grandTotal" => $total,
            "discountTotal" => $discount,
            "cardCharge" => 0
        );
    }

    public function getOrderPayment($paymentType, $paymentValue, $amount)
    {
        return  array(
            "token" => array(
                "type" => $paymentType,
                "value" => $paymentValue,
                "deviceData" => "ff6460d7dd75564e1123d5525d67bf72"
            ),
            "amount" => (int)$amount
        );
    }

    /**
     * @return string
     */
    public function getReferences()
    {
        return strtoupper($this->faker->md5);
    }

    /**
     * @return array
     */
    public function getPushTokens()
    {
        $ios = $this->faker->md5;
        $andriod = $this->faker->md5;
        $pushTokens = array("ios"=>$ios, "android"=>$andriod);
        return $pushTokens;
    }

    /**
     * @return bool
     */
    public function getMarketing()
    {
        $marketing = true;
        return $marketing;
    }

    /**
     * @return array
     */
    public function getSegment()
    {
        $segment = array("students");
        //$segment = ["students"];
        return $segment;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $status = 'ACTIVE';
        return $status;
    }
}