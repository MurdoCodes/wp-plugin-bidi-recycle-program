<?php 
namespace Includes\Base;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '\wp-load.php' );



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

	$SubmitModel = new DBModel();

	// Insert Return Information Data from the form to wp_bidi_return_information table
	$insertReturnInformation = $SubmitModel->insertReturnInformation($return_code, $total_prod_qty, $current_date, $return_status, $customer_id);

	// Get Latest inserted ID from wp_bidi_return_information table
	$return_id = $insertReturnInformation[0];

	// Loop to product details array and save each to wp_bidi_return_product_info table
	for ($x = 0; $x < $count; $x++) {
		$insertProductInformation = $SubmitModel->insertProductInformation($product_name[$x], $product_order_id[$x], $product_item_id[$x], $product_image[$x], $current_date, $return_id, $return_code);
	}
	echo $insertProductInformation;

}