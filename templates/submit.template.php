<?php 
namespace Includes\Base;
require "../vendor/autoload.php";
require_once( dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) )) . '\wp-load.php' );

// use \Includes\Base\SubmitModel;


// var_dump($_POST);

if($_POST){

	$product_name = $_POST['product_name'];
	$product_order_id = $_POST['order_id'];
	$product_item_id = $_POST['order_item_id'];
	$product_image = $_POST['product_img'];
	$product_qty = $_POST['product_qty'];

	$return_code = $_POST['return_code'];
	$total_prod_qty = array_sum($product_qty);	
	$current_date = date('Y-m-d h:i:sa', strtotime("now"));

	$return_status = "PENDING";
	$customer_id = $_POST['current_user_id'];


	$from_firstname = $_POST['from_firstname'];
	$from_lastName = $_POST['from_lastName'];
	$from_email = $_POST['from_email'];
	$from_address = $_POST['from_address'];
	$from_phone_number = $_POST['from_phone_number'];
	$from_country = $_POST['from_country'];
	$from_postcode = $_POST['from_postcode'];
	$from_city = $_POST['from_city'];
	$from_state = $_POST['from_state'];

	$optradio = $_POST['optradio'];

	$cardnumber = $_POST['cardnumber'];
	$expiryDate = $_POST['expiryDate'];
	$cardcode = $_POST['cardcode'];
	$email = $_POST['email'];

	$count = count($product_order_id);

	$SubmitModel = new SubmitModel();
	$insertReturnInformation = $SubmitModel->insertReturnInformation($return_code, $total_prod_qty, $current_date, $return_status, $customer_id);

	$return_id = $insertReturnInformation[0];


	for ($x = 0; $x < $count; $x++) {
	    echo "</br>Order Id : " .$product_order_id[$x];
		echo "</br>Order Item ID : " .$product_item_id[$x];
		echo "</br>Product Name : " .$product_name[$x];
		echo "</br>Product Image : " .$product_image[$x];		
		echo "</br>Product Quantity : " . $product_qty[$x];
		echo "</br></br>";

		$SubmitModel->insertProductInformation($product_name[$x], $product_order_id[$x], $product_item_id[$x], $product_image[$x], $current_date, $return_id, $return_code);
	}



	// $insertReturnInformation = $DB->insertReturnInformation();
	// var_dump($insertReturnInformation);
	// echo echo "</br> RECENT INSERT ID : " . $insertReturnInformation;

	// $insertProductInformation = insertProduct($order_id, $order_item_id, $product_img, $product_name, $product_qty, $current_date);


	
	

	// echo "</br> RETURN CODE : " . $return_code;
	// echo "</br> CURRENT DATE : " . $current_date;

	

	// echo "</br>First Name : " . $from_firstname;
	// echo "</br>Last Name : " . $from_lastName;
	// echo "</br>Email : " . $from_email;
	// echo "</br>Address : " . $from_address;
	// echo "</br>Phone Number " . $from_phone_number;
	// echo "</br>Country : " . $from_country;
	// echo "</br>Post Code : " . $from_postcode;
	// echo "</br>City : " . $from_city;
	// echo "</br>State : " . $from_state;

	// echo "</br>Radio Button : " . $optradio;

	// echo "</br>Card Number : " . $cardnumber;
	// echo "</br>Expiry Date : " . $expiryDate;
	// echo "</br>Card Code : " . $cardcode;
	// echo "</br>Email : " . $email;
}