<?php
/**
* @package Bidi Recycle Program
*/
use Includes\Base\DBModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Includes\Base\Email;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

if(isset($_POST)){
	$DBModel = new DBModel();

	if($_POST['transaction_status'] == 'wc-recycled'){
		$customer_id = $_POST['customer_id'];
		$customer_email = $_POST['customerEmail'];
		$shipping_tracking_number = $_POST['shipping_tracking_number'];
		$return_id = $_POST['return_id'];
		$transaction_date = $_POST['transaction_date'];
		$transaction_status = $_POST['transaction_status'];
		$order_ids = $_POST['order_ids'];
		$product_item_id = $_POST['product_item_id'];


		$DBModel->saveAdminTransaction($transaction_date, $transaction_status, $return_id, $shipping_tracking_number);
		$DBModel->updateReturnInformation($transaction_status, $shipping_tracking_number);

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


		/** SEND EMAIL TO ADMIN **/
		$Email = new Email();
		$mailAdmin = new PHPMailer(true);
		
		$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/adminHeader.jpg";

			try {
				// GET SENDER EMAIL SETTING
				$senderEmailSetting = $Email->senderEmailSetting();
				$senderEmail = $senderEmailSetting['senderEmail'];
				$senderPassword = $senderEmailSetting['senderPassword'];

				// RECEIVER EMAIL
				// $receiverEmail = 'murdoc21daddie@gmail.com';
				$receiverEmail = $customer_email;
				

				// MAIL SETTINGS
				$mailAdmin->SMTPDebug  = $senderEmailSetting['SMTPDebug'];
				$mailAdmin->isSMTP();    
			    $mailAdmin->Host       = $senderEmailSetting['Host'];
			    $mailAdmin->SMTPAuth   = $senderEmailSetting['SMTPAuth'];
			    $mailAdmin->Username   = $senderEmail;
			    $mailAdmin->Password   = $senderPassword;
			    $mailAdmin->SMTPSecure = $senderEmailSetting['SMTPSecure'];
			    $mailAdmin->Port       = $senderEmailSetting['Port'];

			    //Recipients
			    $mailAdmin->setFrom($senderEmail, 'Bidi Vapor - Bidi Recycle');
			    // $mailAdmin->addAddress($from_email, $customerFullName);
			    $mailAdmin->addAddress($receiverEmail, $customerFullName);
			    $mailAdmin->addEmbeddedImage($logoFileUrl, 'bidi_logo');
			    // Attachments
			    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

			    // Content
			    $mailAdmin->isHTML(true);
			    $mailAdmin->Subject = 'Your Bidi Cares Recycling Coupon';
			    $mailAdmin->Body = '<div style="width:50%;">
	    				<img style="width:100%;" src="cid:bidi_logo" alt="Bidi Cares">

						<p>Hello, '.$customerFullName.'</p>
						</br>
						<p>Congratulations on taking the bold step to join our recycling program! We are now processing your Bidi Sticks for recycling. </p>
						</br>
						<p>To further proceed in claiming your FREE Bidi Stick on your next purchase, this is your coupon code. You can also claim your coupon on the nearest retail partners</p>
						</br>			
						<h2>((  '.$coupon_code.'  ))</h2>
						</br>
						<p>You can now claim your <b>FREE Bidi Stick</b> together with your next purchase by inputting your provided coupon code at www.bidivapor.com or the <b>nearest retail store</b> upon your purchase checkout. <b>The delivery fee is free of charge for your free Bidi Stick!</b></p>
						</br>
						<p>If you have any questions or concerns about the Bidi Cares program, you may visit our <a href="https://bidicares.com/about-us/">FAQ page</a>. You may also write to us by sending a message to <a href="mailto:mailto:support@bidivapor.com">support@bidicares.com</a> or through our <a href="https://bidicares.com/contact/">Contact Page</a>, and we will be glad to answer your concerns</p>
						<h4 style="text-align:center;">Save Your Bidi. Save Our Planet.</h4>
						</br>
						<p>Cheers,</p>
						</br></br>
						<p>Bidi Cares Team</p>
						</div>
					</div>
					<hr>
					<p>If you are interested in knowing more about the Bidi Cares program, you may visit our <a href="https://bidicares.quikfillrx.org/about-bidi-stick/">FAQ page</a> or through our <a href="https://bidicares.quikfillrx.org/contact/">Contact Page</a>.</p>
				</div>
				';

			    $mailAdmin->send();
			    echo 'Message has been sent';
			} catch (Exception $e) {
			    echo "Message could not be sent. Mailer Error: {$mailAdmin->ErrorInfo}";
			}
	}

}