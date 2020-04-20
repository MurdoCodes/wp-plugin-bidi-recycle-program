<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\CustomerOrder;
use Includes\Base\DBModel;
use Includes\StampsAPI\StampService;
use Includes\StampsAPI\Address;
use Includes\StampsAPI\Credentials;
use Includes\AuthorizeNet_API\AuthorizeNetService;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

if(isset($_POST)){
	$SubmitModel = new DBModel();
	$StampService = new StampService();
	$CustomerOrderObj = new CustomerOrder();
	$AuthorizeService = new AuthorizeNetService();

	$product_name = $_POST['product_name'];
	$product_order_id = $_POST['order_id'];
	$product_item_id = $_POST['order_item_id'];
	$product_image = $_POST['product_img'];
	$product_qty = $_POST['product_qty'];
	$totalQty = $_POST['product_qty'][0];

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

	$totalItemWeight = $_POST['totalItemWeight'];
	
	// STAMPS START
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
	
	$rate = $StampService->getRates($cleansedAddress->ZIPCode,0,$totalItemWeight,'US-FC', 'Thick Envelope');

	$generateShippingLabel = $StampService->generateShippingLabel($cleansedAddress, $rate);
	$TrackingNumber = $generateShippingLabel['TrackingNumber'];
	$StampsTxID = $generateShippingLabel['StampsTxID'];
	$postageURL = $generateShippingLabel['URL'];

	$rates = $generateShippingLabel['Rate'];
	$ShipDate = $rates['ShipDate'];
	$DeliveryDate = $rates['DeliveryDate'];
	$MaxAmount = $rates['MaxAmount'];
	// STAMPS END

	// AUTHORIZE.NET START
	$creditCardNumber = $_POST['creditCardNumber'];
	$card_exp_month = $_POST['card_exp_month'];
	$card_cvc = $_POST['card_cvc'];

	$cardDetails = array(
		'card-number' => $creditCardNumber,
		'year-month' => $card_exp_month,
		'card-cvc' => $card_cvc
	);

	$AuthorizeService->chargeCreditCard($cardDetails, $MaxAmount, $customer_id, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_state, $from_postcode, $from_country);
	// AUTHORIZE.NET END

	
	$count = count($product_order_id);

	
	// Insert Return Information Data from the form to wp_bidi_return_information table
	$insertReturnInformation = $SubmitModel->insertReturnInformation($total_prod_qty, $current_date, $return_status, $customer_id, $TrackingNumber);
	// Get Latest inserted ID from wp_bidi_return_information table
	$return_id = $insertReturnInformation[0];

	// Insert Shipping Information
	$insertShippingInformation = $SubmitModel->insertShippingInformation($TrackingNumber, $StampsTxID, $postageURL, $ShipDate, $DeliveryDate, $MaxAmount, $return_id);

	// Loop to product details array and save each to wp_bidi_return_product_info table
	for ($x = 0; $x < $count; $x++) {

		$insertProductInformation = $SubmitModel->insertProductInformation($product_name[$x], $product_qty[$x], $product_order_id[$x], $product_item_id[$x], $product_image[$x], $current_date, $return_id, $TrackingNumber);

		$currentProductQuantity = $CustomerOrderObj->getOrderItemQty( $product_order_id[$x], $product_item_id[$x] );

		if($currentProductQuantity = $product_qty[$x]){

			$zero = 0;
			wc_update_order_item_meta( $product_item_id[$x], '_qty', '0' );

		}else if($currentProductQuantity < $product_qty[$x]){

			wc_update_order_item_meta( $product_item_id[$x], '_qty', $product_qty[$x] );

		}else if($currentProductQuantity > $product_qty[$x]){

			$total = $currentProductQuantity - $product_qty[$x];
			wc_update_order_item_meta( $product_item_id[$x], '_qty', $total );

		}

	}


	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);
	// Site logo
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/adminHeader.jpg";

	try {
	    //Server settings
	    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
	    $mail->isSMTP();
	    $mail->Host       = 'smtp.gmail.com';
	    $mail->SMTPAuth   = true;
	    $mail->Username   = 'quickfillkim@gmail.com';
	    $mail->Password   = 'kim123!@#';
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	    $mail->Port       = 465;

	    //Sender
	    $mail->setFrom('quickfillkim@gmail.com', 'Bidi Vapor - Bidi Recycle');
	    // Receiver
	    $mail->addAddress('murdoc21daddie@gmail.com', $customerFullName);

	    $mail->addEmbeddedImage($logoFileUrl, 'bidi_logo');
	    
	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = 'Your Bidi Cares Return Label';
	    $mail->Body = '<div style="width:50%;">
	    				<img src="cid:bidi_logo" alt="Bidi Cares" style="width:100%;">

						<p>Hello, '.$customerFullName.'!</p>
						</br>
						<p>We are grateful for your participation in our recycling program!</p>
						</br>
						<p>As an environmental advocate, we want to lessen our product’s impact on the planet through Bidi Cares, our eco platform. It is the only program in the vaping industry that helps protect our planet from further degradation. Now that you joined our recycling activity, we are positive that we can make a positive impact together. </p>
						</br>
						</br>
						<p>Your return label is attached to this message. </p>
						</br>
						<p>Do your part through these simple steps:</p>
						<ol>
							<li>Ship your used Bidi Sticks to our facility.</li>
							<li>The coupon code for your <b><u>FREE Bidi Stick</u></b> will be sent right after your items have arrived in our facility and have been validated by our staff.</li>
							<li>3.	The coupon code will include the instructions on how to redeem your <b><u>FREE Bidi Stick</u></b> on your next purchase.</li>
						</ol>
						</br></br>
						<p>If you are interested in our environmental program, visit our Bidi Cares website. For further questions, don’t hesitate to contact us at <a href="mailto:support@bidivapor.com">support@bidivapor.com</a> or go to our <a href="bidivapor.org/faq/"></a>FAQs page</p>
						</br>
						<p>Thank you, Bidi eco-warrior</p>';
	    $mail->Body .= '
							
								<div>
									<header style="padding:1em;background-color:#37b348;">
										<h2 style="color:#fff;">Thank You For Choosing Bidi Recycle</h2>
										<h3 style="color:#fff;">Recycle Tracking Number : ' . $TrackingNumber . '</h3>
										<a href="'.$postageURL.'"><button>Click Here To Print Postage Label</button></a>
									</header>
									<div style="padding:1em;background-color:#fdfdfd;border:1px solid #eeeeee;color:#717983;">
										<p>Your Recycle has been received and is now being processed.</br>Your Recycle details are shown below for your reference:</p>									
										<table style="border:1px solid #eeeeee;">
										  <thead>
										    <tr style="border:1px solid #eeeeee;">
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
										    </tr>
										  </thead>
										  <tbody>';
										  	for ($x = 0; $x < $count; $x++) {
										  		$mail->Body .= '
											    <tr style="border:1px solid #eeeeee;">
											      <td style="padding:1em;border:1px solid #eeeeee;">' . $product_name[$x] . '</td>
											      <td style="padding:1em;text-align:center;border:1px solid #eeeeee;">' . $product_qty[$x] . '</td>
											    </tr>';
											    }
		$mail->Body .='
										  </tbody>
										  <tfoot>
										    <tr style="border:1px solid #eeeeee;">
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Product</th>
										      <th style="padding:.5em;background-color: #4CAF50;color: white;">Quantity</th>
										    </tr>
										  </tfoot>
										</table>
									</div>
								</div>
								</br>
								<hr>
								<p>If you are interested in knowing more about the Bidi Cares program, you may visit our <a href="https://bidicares.quikfillrx.org/about-bidi-stick/">FAQ page</a> or through our <a href="https://bidicares.quikfillrx.org/contact/">Contact Page</a>.</p>
							</div>
							';

	    $mail->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	
	echo adminEmail($TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_postcode, $from_state, $totalQty);
}

function adminEmail($TrackingNumber, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_postcode, $from_state, $totalQty){

	$adminEmail = get_option( 'admin_email' );
	$blogname = 'Bidi Vapor Admin';
	// Instantiation and passing `true` enables exceptions
	$mailAdmin = new PHPMailer(true);
	// Site logo
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/adminHeader.jpg";

	try {
	    //Server settings
	    $mailAdmin->SMTPDebug = SMTP::DEBUG_SERVER;
	    $mailAdmin->isSMTP();
	    $mailAdmin->Host       = 'smtp.gmail.com';
	    $mailAdmin->SMTPAuth   = true;
	    $mailAdmin->Username   = 'quickfillkim@gmail.com';
	    $mailAdmin->Password   = 'kim123!@#';
	    $mailAdmin->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	    $mailAdmin->Port       = 465;

	    $customerFullName = $from_firstname . " " . $from_lastName;
	    //Sender
	    $mailAdmin->setFrom('quickfillkim@gmail.com', 'Bidi Vapor - Bidi Recycle');
	    // Receiver
	    $mailAdmin->addAddress('murdoc21daddie@gmail.com', $customerFullName);

	    $mailAdmin->addEmbeddedImage($logoFileUrl, 'bidi_logo');
	    
	    // Attachments
	    // $mailAdmin->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mailAdmin->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mailAdmin->isHTML(true);
	    $mailAdmin->Subject = 'Your Bidi Cares Return Label';
	    $mailAdmin->Body = '<div style="width:50%;">
	    				<img src="cid:bidi_logo" alt="Bidi Cares" style="width:100%;">

						<p>Dear, '.$blogname.'</p>
						</br>
						<p>Greetings!</p>
						</br>
						<p>Through the Bidi Cares Program, we encourage our customers to give back 10 used Bidi Sticks in exchange for a free one. This is part of our mission to make Bidi more recyclable and save the environment from the dangers of nicotine and improper battery disposal.</p>
						</br>
						<h3>'.$customerFullName.' has sent his/her Bidi Sticks with a Tracking Number : '.$TrackingNumber.' to our facility for recycling, together with the return label.</h3>
						</br></br>
						<p>The complete details of this shipment are found below:</p>
						</br>
						<h3 style="text-align:center;">Bidi Cares Recycling</h3>
						<p>First Name: '.$from_firstname.'</p>
						<p>Last Name: '.$from_lastName.'</p>
						<p>Email Address: '.$from_email.'</p>
						<p>Phone: '.$from_phone_number.'</p>
						<p>Street Address: '.$from_address.'</p>
						<p>City: '.$from_city.'</p>
						<p>Zip Code: '.$from_postcode.'</p>
						<p>US State: '.$from_state.'</p>
						<p>Quantity of Bidi Stick you want to recycle: '.$totalQty.'</p>
						</br>
						<h4>Please approve and validate recycled items. Once approved, please complete recycle process by pressing the complete button. This will automatically send them a coupon email.</h4>
						</br></br>
						<p>Cheers,</p>
						</br>
						<p>Bidi Cares Team</p>
						';

	    $mailAdmin->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mailAdmin->ErrorInfo}";
	}
}