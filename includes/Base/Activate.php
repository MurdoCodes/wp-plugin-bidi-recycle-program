<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class Activate {

	public static function activate(){
		self::createTableReturnInformation();
		self::createTableReturnProductInfo();
		self::createTableReturnTransaction();
		self::createTableReturnShipping();
		flush_rewrite_rules();
	}	

	private static function createTableReturnInformation(){
		global $wpdb;
		$bidi_return_information = $wpdb->prefix . 'bidi_return_information';
		$charset_collate = $wpdb->get_charset_collate();
		if($wpdb->get_var( "show tables like '$bidi_return_information'" ) != $bidi_return_information ){

			$sql = "CREATE TABLE {$bidi_return_information} (
						return_id INT(11) NOT NULL AUTO_INCREMENT,
						return_total_qty_returned INT(11) NOT NULL,
						return_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						return_item_status TEXT NOT NULL,
						customer_id INT(11) NOT NULL,
						shipping_tracking_number VARCHAR(100) NOT NULL,
						PRIMARY KEY ( return_id ),
						FOREIGN KEY ( shipping_tracking_number ) REFERENCES ".$wpdb->prefix ."bidi_return_shipping_info ( shipping_tracking_number )
					) {$charset_collate}";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
		}
	}

	private static function createTableReturnProductInfo(){
		global $wpdb;
		$return_product_info = $wpdb->prefix . 'bidi_return_product_info';
		$charset_collate = $wpdb->get_charset_collate();
		if($wpdb->get_var( "show tables like '$return_product_info'" ) != $return_product_info ){
			$sql = "CREATE TABLE {$return_product_info} (
						product_info_id INT(11) NOT NULL AUTO_INCREMENT,
						product_name VARCHAR(50) NOT NULL,
						product_qty VARCHAR(50) NOT NULL,
						product_order_id INT(11) NOT NULL,
						product_item_id INT(11) NOT NULL,
						product_image VARCHAR(255) NOT NULL,
						product_return_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						return_id INT(11) NOT NULL,
						shipping_tracking_number VARCHAR(100) NOT NULL,
						PRIMARY KEY ( product_info_id ),
						FOREIGN KEY ( return_id, shipping_tracking_number ) REFERENCES ".$wpdb->prefix ."bidi_return_information ( return_id, shipping_tracking_number )
					) {$charset_collate}";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
		}
	}

	private static function createTableReturnTransaction(){
		global $wpdb;
		$bidi_return_transaction = $wpdb->prefix . 'bidi_return_transaction';
		$charset_collate = $wpdb->get_charset_collate();
		if($wpdb->get_var( "show tables like '$bidi_return_transaction'" ) != $bidi_return_transaction ){
			$sql = "CREATE TABLE {$bidi_return_transaction} (
						transaction_id INT(11) NOT NULL AUTO_INCREMENT,
						transaction_date_processed DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						transaction_status TEXT NOT NULL,
						return_id INT(11) NOT NULL,
						shipping_tracking_number VARCHAR(100) NOT NULL,
						PRIMARY KEY  ( transaction_id ),
						FOREIGN KEY ( return_id, shipping_tracking_number ) REFERENCES ". $wpdb->prefix ."bidi_return_information ( return_id, shipping_tracking_number )
					) {$charset_collate}";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
		}
	}

	private static function createTableReturnShipping(){
		global $wpdb;
		$bidi_return_shipping_info = $wpdb->prefix . 'bidi_return_shipping_info';
		$charset_collate = $wpdb->get_charset_collate();
		if($wpdb->get_var( "show tables like '$bidi_return_shipping_info'" ) != $bidi_return_shipping_info ){
			$sql = "CREATE TABLE {$bidi_return_shipping_info} (
						shipping_id INT(11) NOT NULL AUTO_INCREMENT,
						shipping_tracking_number VARCHAR(100) NOT NULL,
						shipping_stamps TEXT NOT NULL,
						shipping_postage_url TEXT NOT NULL,
						shipping_date VARCHAR(20) NOT NULL,
						shipping_delivery_day VARCHAR(20) NOT NULL,
						shipping_rate DOUBLE NOT NULL,
						return_id INT(11) NOT NULL,
						PRIMARY KEY  ( shipping_id ),
						FOREIGN KEY ( return_id ) REFERENCES ". $wpdb->prefix . "bidi_return_information ( return_id )
					) {$charset_collate}";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
		}
	}	
}