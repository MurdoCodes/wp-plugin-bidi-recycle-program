<?php

// echo json_encode($_POST);
// var_dump($_POST);

if($_POST){
	$return_code = $_POST['return_code'];

	$order_id = $_POST['order_id'];
	$order_item_id = $_POST['order_item_id'];
	$product_img = $_POST['product_img'];
	$product_name = $_POST['product_name'];
	$productQty = $_POST['productQty'];

	$count = count($order_id);

	echo $count;

	for ($x = 0; $x < $count; $x++) {
	    echo "</br>" .$order_id[$x];
		echo "</br>" .$order_item_id[$x];
		echo "</br>" .$product_img[$x];
		echo "</br>" .$product_name[$x];
		echo "</br>" . $productQty[$x];
		echo "</br></br>";
	}

	$from_firstname = $_POST['return_code'];
	$from_lastName = $_POST['return_code'];
	$from_email = $_POST['return_code'];
	$from_address = $_POST['return_code'];
	$from_phone_number = $_POST['return_code'];
	$from_country = $_POST['return_code'];
	$from_postcode = $_POST['return_code'];
	$from_city = $_POST['return_code'];
	$from_state = $_POST['return_code'];

	$optradio = $_POST['return_code'];

	$cardnumber = $_POST['return_code'];
	$expiryDate = $_POST['return_code'];
	$cardcode = $_POST['return_code'];
	$email = $_POST['return_code'];
}



function returnArrayValue($param){
    
    foreach ($param as $key) {
    	return $key;
    }
}