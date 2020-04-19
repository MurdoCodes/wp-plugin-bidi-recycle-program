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

	$creditCardNumber = $_POST['creditCardNumber'];
	$ExpirationDate = $_POST['ExpirationDate'];
	$returnedRate = $_POST['returnedRate'];
	
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

	$creditCardNumber = $_POST['creditCardNumber'];
	$card_exp_month = $_POST['card_exp_month'];
	$card_cvc = $_POST['card_cvc'];

	$cardDetails = array(
		'card-number' => $creditCardNumber,
		'year-month' => $card_exp_month,
		'card-cvc' => $card_cvc
	);


	var_dump($AuthorizeService->chargeCreditCard($cardDetails, $MaxAmount, $customer_id, $from_firstname, $from_lastName, $from_email, $from_phone_number, $from_address, $from_city, $from_state, $from_postcode, $from_country));

	/**
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
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/logo.png";

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
	    $mail->Subject = 'Bidi Recycle Transaction Summary';
	    $mail->Body = '<div style="width:50%;">
	    				<img src="cid:bidi_logo" alt="Bidi Cares Logs">

						<p>Hello, '.$customerFullName.'</p>
						</br>
						<p>We are grateful for your participation in our recycling program!</p>
						</br>
						<p>As an environmental advocate, we want to lessen our product’s impact on the planet through Bidi Cares, our eco platform. It’s the only program in the vaping industry that helps protect our planet from further degradation. Now that you joined our recycling activity, we are confident that we can make a positive impact together.</p>
						</br>
						</br>
						<p>Do your part through these simple steps:</p>
						<ol>
							<li>Print the return label that we will send 1-2 business days after your transaction.</li>
							<li>Ship your used Bidi Stick to our facility.</li>
							<li>Get a coupon code for a FREE Bidi Stick two days after you ship your sticks. For every 10 Bidi Sticks returned and wait for redemption instructions.</li>
							<li>Redeem your coupon after you receive the activation email for your coupon code. Use this coupon code on your next purchase of Bidi Stick at <a href="www.bidivapor.com">www.bidivapor.com</a> or to a nearest Bidi Stick retail partner</li>
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
										<a href="'.$postageURL.'">Click Here To Print Postage Label</a>
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
							</div>
							';

	    $mail->send();
	    echo 'Message has been sent';
	} catch (Exception $e) {
	    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	**/

}