<?php
/**
* Trigger this file on Plugin uninstall
*
* @package Bidi Recycle Program
*/
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
	die;
}
// Access the database via SQL
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix ."bidi_return_information" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix ."bidi_return_product_info" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix ."bidi_return_transaction" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix ."bidi_return_shipping_info" );