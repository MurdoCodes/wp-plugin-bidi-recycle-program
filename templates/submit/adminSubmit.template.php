<?php 
namespace Includes\Base;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../../vendor/autoload.php";
require_once( dirname (dirname(dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' );

$DBModel = new DBModel();

var_dump($_POST);
$customer_id = $_POST['customer_id'];
$transaction_date = $_POST['transaction_date'];
$return_id = $_POST['return_id'];
$return_code = $_POST['return_code'];
$order_ids = $_POST['order_ids'];
$transaction_status = $_POST['transaction_status'];

foreach ($order_ids as $order_id) {
	$order = wc_get_order( $order_id );
	$order->update_status( $transaction_status );
}

$DBModel->saveAdminTransaction($transaction_date, $transaction_status, $return_id, $return_code);
$DBModel->updateReturnInformation($transaction_status, $return_code);


