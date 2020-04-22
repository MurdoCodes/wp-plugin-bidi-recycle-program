<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email{
	function register() {
			
	}

	function senderEmailSetting(){
		$mail = new PHPMailer(true);

		// $senderEmail = 'quickfillkim@gmail.com';
		// $senderPassword = 'kim123!@#';
		// $senderEmail = 'quikfillrx@gmail.com';
		// $senderPassword = 'OnwardandUpward2021';
		// $receiverEmail = 'quikfillrx.dev@gmail.com';

		//    //GMAIL Server settings
		//    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
		//    $mail->isSMTP();
		//    $mail->Host       = 'smtp.gmail.com';
		//    $mail->SMTPAuth   = true;
		//    $mail->Username   = $senderEmail;
		//    $mail->Password   = $senderPassword;
		//    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		//    $mail->Port       = 465;


		$senderEmail = 'kim@quikfillrx.org';
		$senderPassword = 'kim123!@#';
		$receiverEmail = 'support@bidicares.com';

		$SMTPDebug = SMTP::DEBUG_SERVER;
	    $Host       = 'mail.quikfillrx.org';
	    $SMTPAuth   = true;
	    $SMTPSecure = 'tls';
	    $Port       = 587;

	    $result = array(
	    	'senderEmail' => $senderEmail,
	    	'senderPassword' => $senderPassword,
	    	'receiverEmail' => $receiverEmail,
	    	'SMTPDebug' => $SMTPDebug,
	    	'Host' => $Host,
	    	'SMTPAuth' => $SMTPAuth,
	    	'SMTPSecure' => $SMTPSecure,
	    	'Port' => $Port
	    );

	    return $result;
	}	
}