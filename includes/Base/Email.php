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

		/**
		*	VPS Server settings
		**/
			// $senderEmail = 'support@bidicares.com';
			// $senderPassword = '#BidiC123';
			// $receiverEmail = 'support@bidicares.com';
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;
			// $mail->Host       = 'mail.vps49089.mylogin.co';
			// $mail->SMTPAuth   = true;
			// $mail->Username   = $senderEmail;
			// $mail->Password   = $senderPassword;
			// $mail->SMTPSecure = 'tls';
			// $mail->Port       = 587;

		/**
		*	GMAIL Testing Account Server settings
		**/
		   // $senderEmail 	 = 'quickfillKim@gmail.com';
		   // $senderPassword	 = 'kim123!@#';
		   // $receiverEmail	 = 'murdoc21daddie@gmail.com';
		   // $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
		   // $mail->Host       = 'smtp.gmail.com';
		   // $mail->SMTPAuth   = true;
		   // $mail->Username   = $senderEmail;
		   // $mail->Password   = $senderPassword;
		   // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		   // $mail->Port       = 465;

		/**
		*	QUICKFILL RX SERVER TESTING EMAIL SERVER
		**/
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