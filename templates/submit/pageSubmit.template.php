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
use Includes\Base\Email;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );


	

	/** START SEND EMAIL TO CUSTOMER **/
	// Instantiation and passing `true` enables exceptions
	$Email = new Email();
	$mail = new PHPMailer(true);
	// Site logo
	$logoFileUrl = plugin_dir_path( dirname( __FILE__, 2 ) ) . "assets/img/adminHeader.jpg";

	try {
		// GET SENDER EMAIL SETTING
		$senderEmailSetting = $Email->senderEmailSetting();
		var_dump($senderEmailSetting);
		$senderEmail = $senderEmailSetting['senderEmail'];
		$senderPassword = $senderEmailSetting['senderPassword'];

		// RECEIVER EMAIL
		$receiverEmail = 'murdoc21daddie@gmail.com';
		// $receiverEmail = $from_email;

		// MAIL SETTINGS
		$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->isSMTP();    
	    $mail->Host       = 'mail.vps49089.mylogin.co';
	    $mail->SMTPAuth   = true;
	    $mail->Username   = $senderEmail;
	    $mail->Password   = $senderPassword;
	    $mail->SMTPSecure = 'tls';
	    $mail->Port       = 2525;

	    //Sender
	    $mail->setFrom($senderEmail, 'Bidi Vapor - Bidi Recycle');
	    // Receiver
	    $mail->addAddress($receiverEmail, $customerFullName);
	    // Embeded Header Image
	    $mail->addEmbeddedImage($logoFileUrl, 'bidi_logo');	    
	    // Attachments
	    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = 'Your Bidi Cares Return Label';
	    $mail->Body = '<div style="width:50%;">
	    				<img src="cid:bidi_logo" alt="Bidi Cares" style="width:100%;">

						<p>Hello Lidel</p>
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
	/** START SEND EMAIL TO CUSTOMER **/

	