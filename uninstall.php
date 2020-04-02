<?php 

/**
* Trigger this file on Plugin uninstall
*
* @package Kim Test Plugin
*/

if( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
	die;
}




// Access the database via SQL
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS wp_bidi_return_information" );
$wpdb->query( "DROP TABLE IF EXISTS wp_bidi_return_transaction" );
$wpdb->query( "DROP TABLE IF EXISTS wp_bidi_return_info" );
$wpdb->query( "DROP TABLE IF EXISTS wp_bidi_return_coupon" );
$wpdb->query( "DROP TABLE IF EXISTS wp_return_retailer_setting" );
$wpdb->query( "DROP TABLE IF EXISTS wp_recycle_api_signature" );