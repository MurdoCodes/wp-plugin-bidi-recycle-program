<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\DBModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

$DBModel = new DBModel();

if(isset($_POST)){

	$customer_id = $_POST['customer_id'];
	$return_code = $_POST['return_code'];
	$return_id = $_POST['return_id'];
	$transaction_date = $_POST['transaction_date'];
	$transaction_status = $_POST['transaction_status'];
	$order_ids = $_POST['order_ids'];
	$product_item_id = $_POST['product_item_id'];

	$DBModel->saveAdminTransaction($transaction_date, $transaction_status, $return_id, $return_code);
	$DBModel->updateReturnInformation($transaction_status, $return_code);

	$count = count($order_ids);
	for ($x = 0; $x < $count; $x++) {
		$order = wc_get_order( $order_ids[$x] );
		// Need to call this twice to save on both wp_posts table and wp_wc_order_stats
		$order->update_status( $transaction_status );
		$order->update_status( $transaction_status );
	}

	$coupon_code = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10));

	$coupon_amount = '15.95';
	$discount_type = 'fixed_cart';
	$usage_limit = '1';
						
	$coupon = array(
		'post_title' => $coupon_code,
		'description' => $description,
		'post_content' => '',
		'post_status' => 'publish',
		'post_author' => 1,
		'post_type'		=> 'shop_coupon'
	);
						
	$new_coupon_id = wp_insert_post( $coupon );
						
	// Add meta
	update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
	update_post_meta( $new_coupon_id, 'coupon_amount', $coupon_amount );
	update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
	update_post_meta( $new_coupon_id, 'description', 'Free Bidi Stick for Recycling Items' );
	update_post_meta( $new_coupon_id, 'individual_use', 'no' );
	update_post_meta( $new_coupon_id, 'product_ids', '' );
	update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );	
	update_post_meta( $new_coupon_id, 'expiry_date', '' );
	update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
	update_post_meta( $new_coupon_id, 'free_shipping', 'no' );


	$mail = new PHPMailer(true);

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

		    //Recipients
		    $mail->setFrom('quickfillkim@gmail.com', 'Bidi Vapor - Bidi Recycle');
		    // $mail->addAddress($from_email, $customerFullName);
		    $mail->addAddress('murdoc21daddie@gmail.com', $customerFullName);

		    // Attachments
		    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		    // Content
		    $mail->isHTML(true);
		    $mail->Subject = 'Bidi Recycle Transaction Summary';
		    $mail->Body    = '
								<div style="width:50%;">
									<div>
										<header style="padding:1em;background-color:#37b348;">
											<h2 style="color:#fff;">Thank You For Choosing Bidi Recycle</h2>
										</header>
										<div style="padding:1em;background-color:#fdfdfd;border:1px solid #eeeeee;color:#717983;">
											<p>Your Recycle has been received and is now being processed. Your Recycle details are shown below for your reference:</p>
											<h2>Recycle Code : ' . $return_code . '</h2>
											<h4>Here is a little gift for you :</h4>
											<div>
											<p>Use this coupon to get your free bidi stick</p>
											<h1>' . $coupon_code . '</h1>';

			$mail->Body .='				
											</div>
											<hr>
											<a href="">SHOP NOW</a>						    
										</div>
									</div>
								</div>
								';

		    $mail->send();
		    echo 'Message has been sent';
		} catch (Exception $e) {
		    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}

}