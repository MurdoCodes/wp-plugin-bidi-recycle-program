<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class CustomerOrder{
    	private $customer_orders;
    	public function register() {		
		}
	 	public function return(){
	 		$this->getOrderID();
	 	}      
	 	// Set All Details of Customer Orders
	 	public function setCustomerOrderDetails($customer_orders){
	 		$this->customer_orders = $customer_orders;
	 	}
	 	// Get All Details of Customer Orders
	 	public function getCustomerOrderDetails(){
	 		return $this->customer_orders;
	 	}
	 	// function to get Order ID via get_post
	 	function returnOrderDetails(){
	 		$customer_orders = $this->getCustomerOrderDetails();
	 		foreach ($customer_orders as $value) {
				// use woocommerce function to get list of orders and its details
				$order_id = wc_get_order( $value->ID );
				return $order_id;
		    } 
	 	}
		function getOrderItems(){
			$customer_orders = $this->getCustomerOrderDetails();
			$items = array();
			foreach ($customer_orders as $value) {
				// use woocommerce function to get list of orders and its details
				$order = wc_get_order( $value->ID );
				// use woocommerce function get order items inside single order				
				$order_items = $order->get_items();			
      			// loop to show all the items inside a single order
				foreach( $order_items as $item_id => $item ){					
					$items[] = $item->get_data();
				}
			}
			return($items);
		}
		function getOrderItemQty( $product_order_id, $product_item_id ){
			wc_add_order_item_meta( $product_order_id, '_qty', 2, true );
			$order = wc_get_order( $product_order_id );
			foreach ( $order->get_items() as $item_id => $item ) {
				if($item_id == $product_item_id){
					return $item->get_quantity();
				}
			}
		}

		function getUserBillingShipping($user_id) {
			$data = array(
				"billing_first_name" => get_user_meta( $user_id, 'billing_first_name', true ),
				"billing_last_name" => get_user_meta( $user_id, 'billing_last_name', true ),
				"billing_company" => get_user_meta( $user_id, 'billing_company', true ),
				"billing_address_1" => get_user_meta( $user_id, 'billing_address_1', true ),
				"billing_address_2" => get_user_meta( $user_id, 'billing_address_2', true ),
				"billing_city" => get_user_meta( $user_id, 'billing_city', true ),
				"billing_postcode" => get_user_meta( $user_id, 'billing_postcode', true ),
				"billing_country" => get_user_meta( $user_id, 'billing_country', true ),
				"billing_state" => get_user_meta( $user_id, 'billing_state', true ),
				"billing_email" => get_user_meta( $user_id, 'billing_email', true ),
				"billing_phone" => get_user_meta( $user_id, 'billing_phone', true ),

				"shipping_first_name" => get_user_meta( $user_id, 'shipping_first_name', true ),
				"shipping_last_name" => get_user_meta( $user_id, 'shipping_last_name', true ),
				"shipping_company" => get_user_meta( $user_id, 'shipping_company', true ),
				"shipping_address_1" => get_user_meta( $user_id, 'shipping_address_1', true ),
				"shipping_address_2" => get_user_meta( $user_id, 'shipping_address_2', true ),
				"shipping_city" => get_user_meta( $user_id, 'shipping_city', true ),
				"shipping_postcode" => get_user_meta( $user_id, 'shipping_postcode', true ),
				"shipping_country" => get_user_meta( $user_id, 'shipping_country', true ),
				"shipping_state" => get_user_meta( $user_id, 'shipping_state', true )
			);
		    return $data;
		}
}