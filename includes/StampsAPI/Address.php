<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\StampsAPI;

class Address {
    public $FirstName;
    public $LastName;
    public $Address1;
    public $City;
    public $State;
    public $ZIPCode;
    public $PhoneNumber;
    public $EmailAddress;

    public $CleanseHash;
    public $OverrideHash;

    public function __construct(string $firstName,string $lastName,string $address,string $city,string $state,string $zip,string $phoneNumber,string $email) {

        $this->FirstName = $firstName;
        $this->LastName = $lastName;
        $this->Address1 = $address;
        $this->City = $city;
        $this->State = $state;
        $this->ZIPCode = $zip;
        $this->PhoneNumber = $phoneNumber;
        $this->EmailAddress = $email;
    }
}

class KaivalAddress extends Address {
    public function __construct() {
        Address::__construct(
            'BIDICARES',
            'RETURNS',
            '8630 SW Scholls Ferry Rd Suite 3333',
            'Beaverton',
            'Oregon',
            '97008',
            '8333672434',
            'support@bidivapor.com'
        );
    }
}