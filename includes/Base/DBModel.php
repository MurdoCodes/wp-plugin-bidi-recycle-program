<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class DBModel{

	private $wpdb;

	function register() {
		
	}

	public function __construct( ){
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	// Insert return information to the database
	function insertReturnInformation($return_code, $total_prod_qty, $current_date, $return_status, $customer_id){
		$table = $this->wpdb->prefix . 'bidi_return_information';
		$sql = $this->wpdb->prepare(
			"INSERT INTO `" . $table . "`      
			(`return_id`, `return_code`, `return_total_qty_returned`, `return_date`, `return_item_status`, `customer_id`) 
			values
			(%d, %s, %d, %s, %s, %d)",
			NULL, $return_code, $total_prod_qty, $current_date, $return_status, $customer_id
 		);
		if($this->wpdb->query($sql)){
			return array($this->wpdb->insert_id);
		}

	}

	// Insert return product list and information
	function insertProductInformation($product_name, $product_qty, $product_order_id, $product_item_id, $product_image, $current_date, $return_id, $return_code){
		$table = $this->wpdb->prefix . 'bidi_return_product_info';
		$sql = $this->wpdb->prepare(
			"INSERT INTO `" . $table . "`      
			(`product_info_id`, `product_name`, `product_qty`, `product_order_id`, `product_item_id`, `product_image`, `product_return_date`, `return_id`, `return_code`) 
			values
			(%d, %s, %s, %d, %d, %s, %s, %d, %s)",
			NULL, $product_name, $product_qty, $product_order_id, $product_item_id, $product_image, $current_date, $return_id, $return_code
 		);
		if($this->wpdb->query($sql)){
			return "Success";
		}

	}

	// Get All Data from two tables wp_bidi_return_information and wp_users
	function getAllReturnAndUserData(){
		$table = $this->wpdb->prefix . 'bidi_return_information';
		$table2 = $this->wpdb->prefix . 'users';

		$sql = "SELECT *
				FROM ".$table." " . $table ."
				INNER JOIN ". $table2 ." ON ".$table.".customer_id = ".$table2.".ID
				";
        $result = $this->wpdb->get_results($sql);
        if($result){
			return $result;
		}

	}

	// Get All Return Product Data
	function getReturnProductData($param){
		$bidi_return_information = $this->wpdb->prefix . 'bidi_return_information';
		$bidi_return_product_info = $this->wpdb->prefix . 'bidi_return_product_info';
		$users = $table = $this->wpdb->prefix . 'users';

		$sql = "SELECT *
				FROM " . $bidi_return_information . " wp_bidi_return_information
		 		INNER JOIN " . $bidi_return_product_info . " wp_bidi_return_product_info ON wp_bidi_return_information.return_id = wp_bidi_return_product_info.return_id
		 		INNER JOIN " . $users . " wp_users ON wp_bidi_return_information.customer_id = wp_users.ID
		 		WHERE wp_bidi_return_information.return_id = ".$param."
				";
        $result = $this->wpdb->get_results($sql);
        if($result){
			return $result;
		}
	}

	// Search Return Via Email
	function recycleSearch($param){
		$bidi_return_information = $this->wpdb->prefix . 'bidi_return_information';
		$users = $table = $this->wpdb->prefix . 'users';

		$sql = "SELECT * FROM " . $bidi_return_information . " wp_bidi_return_information
				INNER JOIN " . $users . " wp_users ON wp_bidi_return_information.customer_id = wp_users.ID
				WHERE  wp_users.user_email LIKE '%" . $param . "%'";
        $result = $this->wpdb->get_results($sql);		
        if($result){
			return $result;
		}
	}

	// Sort Table By Date
	function recycleSortingByDate($param){
		$bidi_return_information = $this->wpdb->prefix . 'bidi_return_information';
		$users = $table = $this->wpdb->prefix . 'users';

		$sql = "SELECT * FROM " . $bidi_return_information . " wp_bidi_return_information
				INNER JOIN " . $users . " wp_users ON wp_bidi_return_information.customer_id = wp_users.ID
				ORDER BY return_date " . $param;

        $result = $this->wpdb->get_results($sql);		
        if($result){
			return $result;
		}
	}

	// Sort Table By Status
	function recycleSortingStatus($param){
		$bidi_return_information = $this->wpdb->prefix . 'bidi_return_information';
		$users = $table = $this->wpdb->prefix . 'users';

		$sql = "SELECT * FROM " . $bidi_return_information . " wp_bidi_return_information
				INNER JOIN " . $users . " wp_users ON wp_bidi_return_information.customer_id = wp_users.ID
				WHERE wp_bidi_return_information.return_item_status = '".$param."'
				ORDER BY return_date DESC";
        $result = $this->wpdb->get_results($sql);		
        if($result){
			return $result;
		}
	}

	// Save admin transaction
	function saveAdminTransaction($transaction_date, $transaction_status, $return_id, $return_code){
		$bidi_return_transaction = $this->wpdb->prefix . 'bidi_return_transaction';

		$sql = $this->wpdb->prepare(
				"INSERT INTO `" . $bidi_return_transaction . "`
				(`transaction_id`, `transaction_date_processed`, `transaction_status`, `return_id`, `return_code`)
				VALUES
				(%d, %s, %s, %d, %s)",
				NULL, $transaction_date, $transaction_status, $return_id, $return_code				
		);

		$result = $this->wpdb->get_results($sql);		
        if($result){
			return $result;
		}

	}

	function updateReturnInformation($return_item_status, $return_code){
		$bidi_return_information = $this->wpdb->prefix . 'bidi_return_information';
		$sql = "UPDATE `" . $bidi_return_information . 
				"` SET `return_item_status`= '" . $return_item_status .
				"' WHERE return_code='" . $return_code . "'";

		$result = $this->wpdb->get_results($sql);		
        if($result){
			return $result;
		}
	}
    	
}