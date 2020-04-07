<?php
/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/
namespace Includes\Base;

class Activate {

	public static function activate(){
		self::createTableReturnInformation();
		self::createTableReturnProductInfo();
		self::createTableReturnTransaction();
		self::createTableReturnShipping();
		self::createTableReturnCoupon();
		self::createTableReturnRetailerSetting();
		self::createTableRecycleAPISignature();
		
		flush_rewrite_rules();
	}	

	private static function createTableReturnInformation(){

		global $wpdb;
		$bidi_return_information = $wpdb->prefix . 'bidi_return_information';
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var( "show tables like '$bidi_return_information'" ) != $bidi_return_information ){

			$sql = "CREATE TABLE {$bidi_return_information} (
						return_id INT(11) NOT NULL AUTO_INCREMENT,
						return_code VARCHAR(50) NOT NULL,
						return_total_qty_returned INT(11) NOT NULL,
						return_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						return_item_status TEXT NOT NULL,
						customer_id INT(11) NOT NULL,
						PRIMARY KEY ( return_id, return_code )
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
						product_order_id INT(11) NOT NULL,
						product_item_id INT(11) NOT NULL,
						product_image VARCHAR(255) NOT NULL,
						product_return_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						return_id INT(11) NOT NULL,
						return_code VARCHAR(50) NOT NULL,
						PRIMARY KEY ( product_info_id ),
						FOREIGN KEY ( return_id, return_code ) REFERENCES wp_bidi_return_information ( return_id, return_code )
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
						transaction_notes TEXT NOT NULL,
						return_id INT(11) NOT NULL,
						return_code VARCHAR(50) NOT NULL,
						shipping_id INT(11) NOT NULL,
						PRIMARY KEY  ( transaction_id ),
						FOREIGN KEY ( return_id, return_code ) REFERENCES wp_bidi_return_information ( return_id, return_code ),
						FOREIGN KEY ( shipping_id ) REFERENCES wp_bidi_return_shipping_info ( shipping_id )
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
						shipping_card_number INT(11) NOT NULL,
						shipping_expiry VARCHAR(50) NOT NULL,
						shipping_cvv VARCHAR(50) NOT NULL,
						shipping_date_processed DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						shipping_status TEXT NOT NULL,
						shipping_notes TEXT NOT NULL,
						return_id INT(11) NOT NULL,
						return_code VARCHAR(50) NOT NULL,
						PRIMARY KEY  ( shipping_id ),
						FOREIGN KEY ( return_id, return_code ) REFERENCES wp_bidi_return_information ( return_id, return_code )
					) {$charset_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta( $sql );
		}
	}

	private static function createTableReturnCoupon(){

		global $wpdb;
		$bidi_return_coupon = $wpdb->prefix . 'bidi_return_coupon';
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var( "show tables like '$bidi_return_coupon'" ) != $bidi_return_coupon ){

			$sql = "CREATE TABLE {$bidi_return_coupon} (
						coupon_id INT(11) NOT NULL AUTO_INCREMENT,
						coupon_generated_code VARCHAR(50) NOT NULL,
						coupon_date_created DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
						coupon_status TEXT NOT NULL,
						coupon_notes TEXT NOT NULL,
						transaction_id INT(11) NOT NULL,
						PRIMARY KEY  ( coupon_id ),
						FOREIGN KEY ( transaction_id ) REFERENCES wp_bidi_return_transaction ( transaction_id )
					) {$charset_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta( $sql );
		}

	}

	private static function createTableReturnRetailerSetting(){
		global $wpdb;
		$return_retailer_setting = $wpdb->prefix . 'bidi_return_retailer_setting';
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var( "show tables like '$return_retailer_setting'" ) != $return_retailer_setting ){

			$sql = "CREATE TABLE {$return_retailer_setting} (
						recycle_id INT(11) NOT NULL AUTO_INCREMENT,
						recycle_key VARCHAR(50) NOT NULL,
						recycle_description TEXT NOT NULL,
						recycle_value TEXT NOT NULL,
						PRIMARY KEY  ( recycle_id )
					) {$charset_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta( $sql );
		}
	}

	private static function createTableRecycleAPISignature(){
		global $wpdb;
		$recycle_api_signature = $wpdb->prefix . 'bidi_recycle_api_signature';
		$charset_collate = $wpdb->get_charset_collate();

		if($wpdb->get_var( "show tables like '$recycle_api_signature'" ) != $recycle_api_signature ){

			$sql = "CREATE TABLE {$recycle_api_signature} (
						api_id INT(11) NOT NULL AUTO_INCREMENT,
						api_key VARCHAR(255) NOT NULL,
						api_value VARCHAR(255) NOT NULL,
						PRIMARY KEY  ( api_id )
					) {$charset_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			dbDelta( $sql );
		}
	}
	
}