<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\CustomerOrder;
use Includes\Base\DBModel;
use Includes\StampsAPI\StampService;
use Includes\StampsAPI\Address;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

// Declare CustomerORder Object
$CustomerOrderObj = new CustomerOrder();
$StampService = new StampService();

if($_POST){

	$product_name = $_POST['product_name'];
	$product_order_id = $_POST['order_id'];
	$product_item_id = $_POST['order_item_id'];
	$product_image = $_POST['product_img'];
	$product_qty = $_POST['product_qty'];

	$return_code = $_POST['return_code'];
	$total_prod_qty = array_sum($product_qty);	
	$current_date = date('Y-m-d h:i:sa', strtotime("now"));
	$return_status = "wc-completed";
	$customer_id = $_POST['current_user_id'];

	$from_firstname = $_POST['from_firstname'];
	$from_lastName = $_POST['from_lastName'];
	$customerFullName = $from_firstname . " " . $from_lastName;
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
	

	// STAMPS CLEANSE ADDRESS
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
	$cleanseAddress = $StampService->cleanseAddress($address);
	$cleansedAddress = $cleanseAddress['address'];
	// STAMPS CLEANSE ADDRESS
	$generateShippingLabel = $service->generateShippingLabel($address, $rate);


	// $count = count($product_order_id);

	// $SubmitModel = new DBModel();

	// // Insert Return Information Data from the form to wp_bidi_return_information table
	// $insertReturnInformation = $SubmitModel->insertReturnInformation($return_code, $total_prod_qty, $current_date, $return_status, $customer_id);

	// // Get Latest inserted ID from wp_bidi_return_information table
	// $return_id = $insertReturnInformation[0];

	// // Loop to product details array and save each to wp_bidi_return_product_info table
	// for ($x = 0; $x < $count; $x++) {

	// 	$insertProductInformation = $SubmitModel->insertProductInformation($product_name[$x], $product_qty[$x], $product_order_id[$x], $product_item_id[$x], $product_image[$x], $current_date, $return_id, $return_code);

	// 	$currentProductQuantity = $CustomerOrderObj->getOrderItemQty( $product_order_id[$x], $product_item_id[$x] );

	// 	if($currentProductQuantity = $product_qty[$x]){

	// 		$zero = 0;
	// 		wc_update_order_item_meta( $product_item_id[$x], '_qty', '0' );

	// 	}else if($currentProductQuantity < $product_qty[$x]){

	// 		wc_update_order_item_meta( $product_item_id[$x], '_qty', $product_qty[$x] );

	// 	}else if($currentProductQuantity > $product_qty[$x]){

	// 		$total = $currentProductQuantity - $product_qty[$x];
	// 		wc_update_order_item_meta( $product_item_id[$x], '_qty', $total );

	// 	}

	// }

	// // Instantiation and passing `true` enables exceptions
	// $mail = new PHPMailer(true);

	// try {
	//     //Server settings
	//     $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	//     $mail->isSMTP();
	//     $mail->Host       = 'smtp.gmail.com';
	//     $mail->SMTPAuth   = true;
	//     $mail->Username   = 'quickfillkim@gmail.com';
	//     $mail->Password   = 'kim123!@#';
	//     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	//     $mail->Port       = 465;

	//     //Recipients
	//     $mail->setFrom('quickfillkim@gmail.com', 'Bidi Vapor - Bidi Recycle');
	//     // $mail->addAddress($from_email, $customerFullName);
	//     $mail->addAddress('murdoc21daddie@gmail.com', $customerFullName);

	//     // Attachments
	//     // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//     // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	//     // Content
	//     $mail->isHTML(true);
	//     $mail->Subject = 'Bidi Recycle Transaction Summary';
	//     $mail->Body    = '
	// 						<div style="width:50%;">
	// 							<div>
	// 								<header style="padding:1em;background-color:#37b348;">
	// 									<h2 style="color:#fff;">Thank You For Choosing Bidi Recycle</h2>
	// 								</header>
	// 								<div style="padding:1em;background-color:#fdfdfd;border:1px solid #eeeeee;color:#717983;">
	// 									<p>Your Recycle has been received and is now being processed. Your Recycle details are shown below for your reference:</p>
	// 									<h3>Recycle Code : ' . $return_code . '</h3>
	// 									<table style="border:1px solid #eeeeee;">
	// 									  <thead>
	// 									    <tr style="border:1px solid #eeeeee;">
	// 									      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
	// 									      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
	// 									    </tr>
	// 									  </thead>
	// 									  <tbody>';
	// 									  	for ($x = 0; $x < $count; $x++) {
	// 									  		$mail->Body .= '
	// 										    <tr style="border:1px solid #eeeeee;">
	// 										      <td style="padding:1em;border:1px solid #eeeeee;">' . $product_name[$x] . '</td>
	// 										      <td style="padding:1em;text-align:center;border:1px solid #eeeeee;">' . $product_qty[$x] . '</td>
	// 										    </tr>';
	// 										    }
	// 	$mail->Body .='
	// 									  </tbody>
	// 									  <tfoot>
	// 									    <tr style="border:1px solid #eeeeee;">
	// 									      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
	// 									      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
	// 									    </tr>
	// 									  </tfoot>
	// 									</table>
	// 								</div>
	// 							</div>
	// 						</div>
	// 						';

	//     $mail->send();
	//     echo 'Message has been sent';
	// } catch (Exception $e) {
	//     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	// }
}