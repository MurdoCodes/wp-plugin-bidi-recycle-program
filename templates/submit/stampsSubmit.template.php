<?php
/**
* @package Bidi Recycle Program
*/
use Includes\StampsAPI\StampService;
use Includes\StampsAPI\Address;
require "../../vendor/autoload.php";

if(isset($_POST)){
$StampService = new StampService();

// STAMPS CLEANSE ADDRESS
$from_firstname = $_POST['from_firstname'];
$from_lastName = $_POST['from_lastName'];
$from_address = $_POST['from_address'];
$from_city = $_POST['from_city'];
$from_state = $_POST['from_state'];
$from_postcode = $_POST['from_postcode'];
$from_phone_number = $_POST['from_phone_number'];
$from_email = $_POST['from_email'];
$totalItemQty = $_POST['totalItemQty'];
$totalItemWeight = $_POST['totalItemWeight'];


$address = new Address(
	$from_firstname,
	$from_lastName,
	$from_address,
	$from_city,
	$from_state,
	$from_postcode,
	$from_phone_number,
	$from_email,
);
$res = $StampService->cleanseAddress($address);
$cleansedAddress = $res['address'];

$rate = $StampService->getRates($cleansedAddress->ZIPCode,0,$totalItemWeight,'US-FC', 'Thick Envelope');

echo json_encode($rate);

}