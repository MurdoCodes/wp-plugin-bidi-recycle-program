<?php 

/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/

namespace Includes\Base;

class SubmitModel{	

	function insertReturnInformation($return_code, $total_prod_qty, $current_date, $return_status, $customer_id){
		global $wpdb;

		$sql = $wpdb->prepare(
			"INSERT INTO `wp_bidi_return_information`      
			(`return_id`, `return_code`, `return_total_qty_returned`, `return_date`, `return_item_status`, `customer_id`) 
			values
			(%d, %s, %d, %s, %s, %d)",
			NULL, $return_code, $total_prod_qty, $current_date, $return_status, $customer_id
 		); 		

		if($wpdb->query($sql)){
			return array($wpdb->insert_id);
		}

	}

	function insertProductInformation($product_name, $product_order_id, $product_item_id, $product_image, $current_date, $return_id, $return_code){
		global $wpdb;

		$sql = $wpdb->prepare(
			"INSERT INTO `wp_bidi_return_product_info`      
			(`product_info_id`, `product_name`, `product_order_id`, `product_item_id`, `product_image`, `product_return_date`, `return_id`, `return_code`) 
			values
			(%d, %s, %d, %d, %s, %s, %d, %s)",
			NULL, $product_name, $product_order_id, $product_item_id, $product_image, $current_date, $return_id, $return_code
 		); 		

		if($wpdb->query($sql)){
			return "Success";
		}
	}
    	
}

