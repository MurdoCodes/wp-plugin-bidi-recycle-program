<?php
/**
* @package Bidi Recycle Program
*/
/*
Plugin Name: Bidi Recycle Program
Plugin URI: https://bidivapor.com
Description: This is a plugin that would allow customers to do a return of product where they can get 1pc Free Bidi Stick in every 10pcs of Bidi Product Returned
Version: 1.0.0
Author: QuickFillRX
Author URI : http://quickfillrx.com/
License: GPLv2 or later
Text Domain: bidi-recycle-program
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the license, or (at your option) an later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY of FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public Icense for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
Copyright 2005-2015 Automatic, Inc.
*/
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

// Load Composer Vendor Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ){
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// Initialize Activation, The code that runs during plugin activation
function activate_bidi_recycle_plugin(){
    Includes\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_bidi_recycle_plugin' );

// Initialize Deactivation, The code that runs during plugin deactivation
function deactivate_bidi_recycle_plugin(){
    Includes\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_bidi_recycle_plugin' );

// Include the Init folder, Initialize all the core classes of the plugin
if ( class_exists( 'Includes\\Init' ) ) {
    Includes\Init::register_services();
}

// Register new status
function register_recycle_order_status() {
    register_post_status( 'wc-recycled', array(
        'label'                     => 'Recycled',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Recycled (%s)', 'Recycled (%s)' )
    ) );
}
add_action( 'init', 'register_recycle_order_status' );
        
// Add to list of WC Order statuses
function add_recycle_to_order_statuses( $order_statuses ) {
 
    $new_order_statuses = array();
 
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
 
        $new_order_statuses[ $key ] = $status;
 
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-recycled'] = 'Recycled';
        }
    }
 
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_recycle_to_order_statuses' );