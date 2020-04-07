<?php
/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class CustomerOrder{

    	private $customer_orders;

	 	function return(){
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
}