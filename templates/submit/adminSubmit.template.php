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

$DBModel->saveAdminTransaction($transaction_date, $transaction_status, $return_id, $return_code);
$DBModel->updateReturnInformation($transaction_status, $return_code);

$count = count($order_ids);
for ($x = 0; $x < $count; $x++) {
	$order = wc_get_order( $order_ids[$x] );
	// Need to call this twice to save on both wp_posts table and wp_wc_order_stats
	$order->update_status( $transaction_status );
	$order->update_status( $transaction_status );
}

