<?php
/**
* @package Bidi Recycle Program
*/
namespace Includes\Pages;
use \Includes\Base\BaseController;

class Admin extends BaseController {

	public function register() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_filter('autoptimize_filter_imgopt_do_css','__return_false');
	}

	public function add_admin_pages() {
		add_menu_page( 'Bidi Recyle', 'Bidi Recycle', 'manage_options', 'bidi_recycle_program', array( $this, 'admin_index' ), 'dashicons-admin-tools', 110 );		
	}

	public function admin_index(){
		require_once $this->plugin_path . 'templates/admin.template.php';
	}

}